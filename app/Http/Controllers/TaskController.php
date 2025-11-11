<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Task;
use App\Models\Region;
use App\Models\District;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::orderBy('created_at', 'desc')->get();

        return view('staffs.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staffs.tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::create($request->only('title', 'description', 'priority', 'due_date'));

        // return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
        return redirect()->route('tasks.assign', $task->id)->with('success', 'Task created! Now assign staff.');
    }


    public function assignFormxx(Task $task)
    {
        $staff = Staff::where('status', 'active')->get();
        return view('staffs.tasks.assign', compact('task', 'staff'));
    }
    public function assignForm(Request $request, Task $task)
    {
        $staff = Staff::query()
            ->where('status', 'active')
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->when($request->designation, fn($q) => $q->where('designation', $request->designation))
            ->when($request->rank, fn($q) => $q->where('rank', $request->rank))
            ->get();

        return view('staffs.tasks.assign', [
            'task' => $task,
            'staff' => $staff,
            'regions' => Region::orderBy('name')->get(),
            'districts' => District::orderBy('name')->get(),
            'designations' => Staff::select('designation')->distinct()->pluck('designation'),
            'ranks' => Staff::select('rank')->distinct()->pluck('rank'),
        ]);
    }

    public function assignStaff(Request $request, Task $task)
    {
        foreach ($request->staff_ids as $id) {
            $task->staff()->attach($id, [
                'region_id' => $request->region_id,
                'district_id' => $request->district_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'assigned_at' => now(),
                'is_active' => true,
            ]);
        }

        return redirect()->route('staffs.tasks.index')->with('success', 'Staff assigned successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('staffs.tasks.edit', compact('task'));
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->only('title', 'description', 'priority', 'due_date'));

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function filterStaff(Request $request)
    {
        $staff = Staff::query()
            ->when($request->designation, fn($q) => $q->where('designation', $request->designation))
            ->when($request->rank, fn($q) => $q->where('rank', $request->rank))
            ->when($request->gender, fn($q) => $q->where('gender', $request->gender))
            ->where('status', 'active')
            ->get();

        return response()->json($staff);
    }

}
