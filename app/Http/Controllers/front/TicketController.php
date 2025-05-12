<?php

namespace App\Http\Controllers\front;

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
    public function index()
    {
        return Inertia::render('Tickets/Index', [
            'tickets' => Ticket::where('created_by', auth()->id())
                ->latest()
                ->get()
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

    // Show a single ticket
    public function show(Ticket $ticket)
    {
        //$this->authorize('view', $ticket);

        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket
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
