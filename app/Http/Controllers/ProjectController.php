<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    protected function checkAdminAccess()
    {
        if (!Auth::user()->isAdminOrCustomer()) {
            abort(403);
        }
    }

    public function index()
    {
        $this->checkAdminAccess();
        $user = Auth::user();

        // Admins are treated like customers for visibility: they see only their own projects
        if ($user->role === 'customer' || $user->role === 'admin') {
            $projects = Project::with(['creator', 'tasks.assignedUser'])
                ->where('created_by', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $projects = Project::with(['creator', 'tasks.assignedUser'])->orderBy('created_at', 'desc')->get();
        }
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $this->checkAdminAccess();

        // Get the developers who will be auto-assigned using round-robin
        $defaultBackend = $this->roundRobinAssigneeForRole('backend');
        $defaultFrontend = $this->roundRobinAssigneeForRole('frontend');
        $defaultServer = $this->roundRobinAssigneeForRole('server');

        return view('projects.create', compact('defaultBackend', 'defaultFrontend', 'defaultServer'));
    }

    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Automatically assign developers using round-robin for each category
        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'start_date' => $validated['start_date'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'created_by' => Auth::id(),
            'status' => 'active',
            'backend_assigned_to' => $this->roundRobinAssigneeForRole('backend'),
            'frontend_assigned_to' => $this->roundRobinAssigneeForRole('frontend'),
            'server_assigned_to' => $this->roundRobinAssigneeForRole('server'),
        ]);

        return redirect()
            ->route('projects.tasks.create', $project)
            ->with('success', 'Project created successfully! Developers assigned automatically using round-robin distribution. Now add tasks to this project.');
    }

    /**
     * Show form to add multiple tasks to a project.
     *
     * 1) Sa loob ng project: same developer per category. Kung may task na sa backend,
     *    lahat ng bagong backend task ay sa kanya pa rin; same sa frontend at server.
     *
     * 2) Bagong project (walang task pa sa category na iyon): round-robin. Hindi ma-assign
     *    ang isang dev kung may ibang dev (same role) na wala pang project — lahat ma-assign
     *    muna bago maulit (e.g. Dev 1 na sa Project 1, hindi siya uulit hanggat hindi na-assign
     *    lahat ng ibang dev).
     */
    public function createTasks(Project $project)
    {
        $this->checkAdminAccess();

        return view('projects.add-tasks', compact('project'));
    }

    /**
     * Round-robin per role: piliin lang ang dev na may pinakakaunting projects.
     * Kung may dev na wala pang project (count=0), siya ang uunahin — hindi ma-assign
     * ang may project na hanggat hindi pa na-assign lahat. Order: Dev 1, Dev 2, … (by name).
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
            ->whereNotNull('project_id')
            ->selectRaw('assigned_to, count(distinct project_id) as project_count')
            ->groupBy('assigned_to')
            ->pluck('project_count', 'assigned_to')
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

    /**
     * Store multiple tasks for a project.
     */
    public function storeTasks(Request $request, Project $project)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'tasks' => ['required', 'array', 'min:1'],
            'tasks.*.title' => ['required', 'string', 'max:255'],
            'tasks.*.description' => ['nullable', 'string'],
            'tasks.*.category' => ['required', 'in:backend,frontend,server'],
            'tasks.*.status' => ['required', 'in:todo,in_progress,in_review,done'],
        ]);

        $count = 0;
        foreach ($validated['tasks'] as $taskData) {
            if (empty(trim($taskData['title'] ?? ''))) {
                continue;
            }
            $category = $taskData['category'];
            $assignedTo = $project->getAssignedToForCategory($category);

            Task::create([
                'title' => $taskData['title'],
                'description' => $taskData['description'] ?? null,
                'assigned_to' => $assignedTo,
                'status' => $taskData['status'],
                'category' => $category,
                'user_id' => Auth::id(),
                'project_id' => $project->id,
            ]);
            $count++;
        }

        if ($count === 0) {
            return redirect()
                ->route('projects.tasks.create', $project)
                ->with('error', 'Please add at least one task with a title.');
        }

        $message = $count === 1 ? '1 task added.' : $count . ' tasks added.';
        return redirect()->route('projects.show', $project)->with('success', $message);
    }

    public function show(Request $request, Project $project)
    {
        // Admins can view any project.
        // Customers can view only projects they created.
        // Developers can view only if they have tasks in the project.
        $user = Auth::user();
        if ($user->role === 'admin' || $user->role === 'customer') {
            // Admins and customers can view only projects they created
            if ($project->created_by !== $user->id) {
                abort(403);
            }
        } else {
            $has = $project->tasks()->where('assigned_to', $user->id)->exists();
            if (! $has) {
                abort(403);
            }
        }

        $tasksQuery = $project->tasks()->with(['assignedUser', 'user', 'comments' => fn ($q) => $q->with('user')->orderBy('created_at', 'desc')]);

        // Optional category filter (backend/frontend/server) – mainly for admin
        $category = $request->query('category');
        $allowedCategories = ['backend', 'frontend', 'server'];
        if ($category && in_array($category, $allowedCategories, true)) {
            $tasksQuery->where('category', $category);
        } else {
            $category = null;
        }

        // If the current user is a developer (not admin/customer), restrict tasks to only those assigned to them
        if (! in_array($user->role, ['admin', 'customer'])) {
            $tasksQuery->where('assigned_to', $user->id);
        }

        // Fetch tasks (admins/customers may see all tasks or filtered by category; developers see only their tasks)
        $tasks = $tasksQuery->get();
        $statuses = ['todo', 'in_progress', 'in_review', 'done'];
        $grouped = collect($tasks)->groupBy('status');

        return view('projects.show', compact('project', 'tasks', 'grouped', 'statuses', 'category'));
    }

    public function edit(Project $project)
    {
        $this->checkAdminAccess();

        $backendUsers = User::where('role', 'backend')->orderBy('name')->get();
        $frontendUsers = User::where('role', 'frontend')->orderBy('name')->get();
        $serverUsers = User::where('role', 'server')->orderBy('name')->get();

        return view('projects.edit', compact('project', 'backendUsers', 'frontendUsers', 'serverUsers'));
    }

    public function update(Request $request, Project $project)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,archived',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
            'backend_assigned_to' => 'nullable|exists:users,id',
            'frontend_assigned_to' => 'nullable|exists:users,id',
            'server_assigned_to' => 'nullable|exists:users,id',
        ]);

        // Track changes for notifications
        $changes = [];
        if ($project->deadline !== $validated['deadline']) {
            $changes['deadline'] = true;
        }
        if ($project->status !== $validated['status']) {
            $changes['status'] = true;
        }
        if ($project->priority !== $validated['priority']) {
            $changes['priority'] = true;
        }

        $project->update($validated);

        // Notify all assigned developers about updates
        if (!empty($changes)) {
            $this->notifyProjectDevelopers($project, $changes);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        $this->checkAdminAccess();

        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully!');
    }

    public function updateStatus(Request $request, Project $project)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'status' => 'required|in:active,completed,overdue',
        ]);

        $project->update(['status' => $validated['status']]);

        // Notify all assigned developers
        $this->notifyProjectDevelopers($project, ['status' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Project status updated successfully',
            'status' => $validated['status']
        ]);
    }

    /**
     * Notify all assigned developers about project changes.
     * Notifies based on what changed (deadline, status, priority).
     */
    protected function notifyProjectDevelopers(Project $project, array $changes)
    {
        $developers = [];

        if ($project->backend_assigned_to) {
            $developers[] = $project->backend_assigned_to;
        }
        if ($project->frontend_assigned_to) {
            $developers[] = $project->frontend_assigned_to;
        }
        if ($project->server_assigned_to) {
            $developers[] = $project->server_assigned_to;
        }

        $message = "Project '{$project->name}' has been updated. ";
        if (isset($changes['status'])) {
            $message .= "Status changed to {$project->status}. ";
        }
        if (isset($changes['deadline'])) {
            $message .= "Deadline updated to {$project->deadline?->format('M d, Y')}. ";
        }
        if (isset($changes['priority'])) {
            $message .= "Priority changed to {$project->priority}. ";
        }

        foreach ($developers as $developerId) {
            Notification::create([
                'user_id' => $developerId,
                'type' => 'project_update',
                'project_id' => $project->id,
                'message' => trim($message),
            ]);
        }
    }
}
