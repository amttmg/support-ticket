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

        return Inertia::render('Tickets/Index', [
            'tickets' => $filteredTickets,
            'statuses' => $statuses,
            'filters' => $request->only(['status']),
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
        ]);

        $validated['status_id'] = TicketStatus::where('name', 'open')->first()->id;
        $request->user()->tickets()->create($validated);

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
        $ticket->load(['status', 'comments', 'creator', 'agents']);

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
        $departments = Department::with('supportUnits')->get();
        return response()->json($departments);
    }

    // Get units for a department
    public function getUnits($departmentId)
    {
        $units = SupportUnit::where('department_id', $departmentId)
            ->with('supportTopics')
            ->get();

        return response()->json($units);
    }

    // Get topics for a unit
    public function getTopics($unitId)
    {
        $topics = SupportTopic::where('support_unit_id', $unitId)->get();
        return response()->json($topics);
    }

    // Get ticket statuses
    public function getStatuses()
    {
        $statuses = TicketStatus::orderBy('order')->get();
        return response()->json($statuses);
    }
}
