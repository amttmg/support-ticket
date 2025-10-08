<?php

namespace App\Http\Controllers\front;

use App\Constants\PermissionConstants;
use App\Constants\TicketStatusConstant;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\SupportTopic;
use App\Models\SupportUnit;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class TicketController extends Controller
{
    // Display all tickets for the authenticated user
    public function index(Request $request)
    {
        $query = auth()->user()->tickets()->with([
            'supportTopic',
            'supportTopic.supportUnit',
            'supportTopic.supportUnit.department',
            'status',
            'creator'
        ]);

        // Handle sorting
        if ($request->has('sort')) {
            $sort = $request->sort;
            $direction = 'asc';

            if (str_starts_with($sort, '-')) {
                $direction = 'desc';
                $sort = substr($sort, 1);
            }

            // Validate sortable fields
            $allowedSorts = ['title', 'created_at', 'updated_at', 'priority'];
            if (in_array($sort, $allowedSorts)) {
                $query->orderBy($sort, $direction);
            }
        } else {
            // Default sorting
            $query->latest();
        }

        $tickets = $query->get();

        $statuses = TicketStatus::orderBy('order')->get()
            ->map(function ($status) use ($tickets) {
                $status->tickets_count = $tickets->where('status_id', $status->id)->count();
                $status->color = $status->color ?? 'gray';
                return $status;
            });

        // Get the default status (Open) if no status filter is applied
        $defaultStatus = TicketStatus::where('id', TicketStatusConstant::OPEN)->first();

        if ($request->status == 'all') {
            $filteredTickets = $tickets;
        } elseif ($request->status) {
            $filteredTickets = $query->clone()->where('status_id', $request->status)->get();
        } elseif ($defaultStatus) {
            $filteredTickets = $query->clone()->where('status_id', $defaultStatus->id)->get();
        } else {
            $filteredTickets = $tickets;
        }

        return Inertia::render('Tickets/Index', [
            'tickets' => $filteredTickets,
            'statuses' => $statuses,
            'filters' => $request->only(['status', 'sort']), // Include sort in filters
            'total_tickets' => $tickets->count(),
            'has_bm_role' => auth()->user()->can(PermissionConstants::PERMISSION_BRANCH_MANAGER),
        ]);
    }

    // Show the ticket creation form
    public function create()
    {
        return Inertia::render('Tickets/Create');
    }

    // Store a new ticket
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'support_topic_id' => 'required|exists:support_topics,id',
            'attachments.*' => 'file|max:10240', // 10MB max each
        ]);

        $validated['status_id'] = TicketStatus::where('name', 'open')->first()->id;
        $ticket = $request->user()->tickets()->create($validated);

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets', 'public');
                $ticket->files()->create([
                    'file_path' => $path,
                    //'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }


        return redirect()->route('tickets.index')
            ->with('success', 'Ticket created successfully!');
    }

    public function branchTickets(Request $request)
    {
        if (!auth()->user()->can(PermissionConstants::PERMISSION_BRANCH_MANAGER)) {
            abort(403, 'Unauthorized action.');
        }

        $query = auth()->user()->branch->tickets()->with([
            'supportTopic',
            'supportTopic.supportUnit',
            'supportTopic.supportUnit.department',
            'status',
            'creator'
        ])->latest();
        $tickets = $query->get();

        $statuses = TicketStatus::orderBy('order')->get()
            ->map(function ($status) use ($tickets) {
                $status->tickets_count = $tickets->where('status_id', $status->id)->count();
                $status->color = $status->color ?? 'gray';
                return $status;
            });

        // Get the default status (Open) if no status filter is applied
        $defaultStatus = TicketStatus::where('id', TicketStatusConstant::OPEN)->first();

        if ($request->status == 'all') {
            $filteredTickets = $tickets;
        } elseif ($request->status) {
            $filteredTickets = $query->where('status_id', $request->status)->get();
        } elseif ($defaultStatus) {
            $filteredTickets = $query->where('status_id', $defaultStatus->id)->get();
        } else {
            $filteredTickets = $tickets;
        }

        return Inertia::render('Tickets/BranchTickets', [
            'tickets' => $filteredTickets,
            'statuses' => $statuses,
            'filters' => $request->only(['status']),
            'total_tickets' => $tickets->count(),
        ]);
    }

    // Show a single ticket
    public function show(Ticket $ticket)
    {
        $ticket->load(['status', 'comments', 'creator', 'agents', 'activities.causer']);
        $comments = $ticket->comments()
            ->with(['user', 'children.user', 'children.children'])
            ->whereNull('parent_id')
            ->get();

        return inertia('Tickets/Show', [
            'ticket' => $ticket,
            'comments' => $comments,
        ]);
    }


    // Get all departments
    public function getDepartments()
    {
        $departments = Cache::remember('departments_with_units', 3600, function () {
            return Department::with('supportUnits')->get();
        });

        return response()->json($departments);
    }

    // Get units for a department
    public function getUnits($departmentId)
    {
        $cacheKey = "units_with_topics_department_$departmentId";

        $units = Cache::remember($cacheKey, 3600, function () use ($departmentId) {
            return SupportUnit::where('department_id', $departmentId)
                ->with('supportTopics')
                ->get();
        });

        return response()->json($units);
    }

    // Get topics for a unit
    public function getTopics($unitId)
    {
        $cacheKey = "topics_for_unit_$unitId";

        $topics = Cache::remember($cacheKey, 3600, function () use ($unitId) {
            return SupportTopic::where('support_unit_id', $unitId)->get();
        });

        return response()->json($topics);
    }
    // Get ticket statuses
    public function getStatuses()
    {
        $statuses = TicketStatus::orderBy('order')->get();
        return response()->json($statuses);
    }

    public function reopen(Ticket $ticket)
    {
        $ticket->update(['status_id' => TicketStatusConstant::OPEN]);

        return back()->with('success', 'Ticket re-opened successfully');
    }

    public function close(Ticket $ticket)
    {
        $ticket->update(['status_id' => TicketStatusConstant::CLOSED]);

        return back()->with('success', 'Ticket closed successfully');
    }
}
