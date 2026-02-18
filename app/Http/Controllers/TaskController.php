<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // If admin or customer, show projects and all tasks (admin-style page)
        if (Auth::user()->isAdminOrCustomer()) {
            $projects = \App\Models\Project::with(['tasks' => function($query) {
                $query->with(['assignedUser', 'user']);
            }])->orderBy('created_at', 'desc')->get();
            
            return view('tasks.admin-index', compact('projects'));
        }

        // For non-admin users, show only their tasks
        $tasks = Task::where('user_id', Auth::id())
            ->with('assignedUser')
            ->get()
            ->groupBy('status');

        $users = User::where('id', '!=', Auth::id())->get();

        return view('tasks.index', compact('tasks', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Task::class);
        $backendUsers = User::where('role', 'backend')->orderBy('name')->get();
        $frontendUsers = User::where('role', 'frontend')->orderBy('name')->get();
        $serverUsers = User::where('role', 'server')->orderBy('name')->get();
        return view('tasks.create', compact('backendUsers', 'frontendUsers', 'serverUsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,in_review,done',
            'category' => 'required|in:backend,frontend,server',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $validated['user_id'] = Auth::id();

        // Auto-assign using round-robin if no developer is selected
        if (empty($validated['assigned_to'])) {
            $validated['assigned_to'] = $this->roundRobinAssigneeForRole($validated['category']);
        }

        Task::create($validated);

        if ($request->project_id) {
            return redirect()->route('projects.show', $request->project_id)->with('success', 'Task created successfully!');
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task->load(['comments.replies.user', 'comments.user']);
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $backendUsers = User::where('role', 'backend')->orderBy('name')->get();
        $frontendUsers = User::where('role', 'frontend')->orderBy('name')->get();
        $serverUsers = User::where('role', 'server')->orderBy('name')->get();
        return view('tasks.edit', compact('task', 'backendUsers', 'frontendUsers', 'serverUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,in_review,done',
            'category' => 'required|in:backend,frontend,server',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.show', $task)->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    /**
     * Update task status (for drag and drop)
     */
    public function updateStatus(Request $request, Task $task)
    {
        // allow either the task creator or the assigned user to update the status
        $user = Auth::user();
        if (!$user->isAdminOrCustomer() && $user->id !== $task->user_id && $user->id !== $task->assigned_to) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,in_review,done',
        ]);

        $task->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'task_id' => $task->id,
            'status' => $task->status,
        ]);
    }

    /**
     * Round-robin per role: assign to the dev with the fewest tasks in this category.
     * Ensures balanced workload distribution across all developers.
     */
    protected function roundRobinAssigneeForRole(string $role): ?int
    {
        $userIds = User::where('role', $role)->orderBy('name')->orderBy('id')->pluck('id')->toArray();
        if (empty($userIds)) {
            return null;
        }

        $counts = Task::query()
            ->where('category', $role)
            ->whereIn('assigned_to', $userIds)
            ->whereNotNull('assigned_to')
            ->selectRaw('assigned_to, count(*) as task_count')
            ->groupBy('assigned_to')
            ->pluck('task_count', 'assigned_to')
            ->toArray();

        $minCount = null;
        foreach ($userIds as $id) {
            $c = (int) ($counts[$id] ?? 0);
            if ($minCount === null || $c < $minCount) {
                $minCount = $c;
            }
        }
        if ($minCount === null) {
            return (int) $userIds[0];
        }

        foreach ($userIds as $id) {
            $c = (int) ($counts[$id] ?? 0);
            if ($c === $minCount) {
                return (int) $id;
            }
        }

        return (int) $userIds[0];
    }
}
