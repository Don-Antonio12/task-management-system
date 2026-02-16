<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
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

        $projects = Project::with(['creator', 'tasks.assignedUser'])->orderBy('created_at', 'desc')->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $this->checkAdminAccess();

        return view('projects.create');
    }

    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'created_by' => Auth::id(),
            'status' => 'active',
        ]);

        // After creating a project, go to add multiple tasks for this project
        return redirect()
            ->route('projects.tasks.create', $project)
            ->with('success', 'Project created successfully! Now add tasks to this project.');
    }

    /**
     * Show form to add multiple tasks to a project.
     */
    public function createTasks(Project $project)
    {
        $this->checkAdminAccess();
        $backendUsers = User::where('role', 'backend')->orderBy('name')->get();
        $frontendUsers = User::where('role', 'frontend')->orderBy('name')->get();
        $serverUsers = User::where('role', 'server')->orderBy('name')->get();
        return view('projects.add-tasks', compact('project', 'backendUsers', 'frontendUsers', 'serverUsers'));
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
            'tasks.*.deadline' => ['nullable', 'date'],
            'tasks.*.assigned_to' => ['nullable', 'exists:users,id'],
            'tasks.*.status' => ['required', 'in:todo,in_progress,in_review,done'],
            'tasks.*.category' => ['required', 'in:backend,frontend,server'],
        ]);

        $count = 0;
        foreach ($validated['tasks'] as $taskData) {
            if (empty(trim($taskData['title'] ?? ''))) {
                continue;
            }
            Task::create([
                'title' => $taskData['title'],
                'description' => $taskData['description'] ?? null,
                'deadline' => $taskData['deadline'] ?? null,
                'assigned_to' => $taskData['assigned_to'] ?? null,
                'status' => $taskData['status'],
                'category' => $taskData['category'],
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
        // Allow admin/customer to view any project; developers can view only if they have tasks in the project
        $user = Auth::user();
        if (!$user->isAdminOrCustomer()) {
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
        if (!$user->isAdminOrCustomer()) {
            $tasksQuery->where('assigned_to', $user->id);
        }
        $tasks = $tasksQuery->get();
        $statuses = ['todo', 'in_progress', 'in_review', 'done'];
        $grouped = collect($tasks)->groupBy('status');

        return view('projects.show', compact('project', 'tasks', 'grouped', 'statuses', 'category'));
    }

    public function edit(Project $project)
    {
        $this->checkAdminAccess();

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,archived',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        $this->checkAdminAccess();

        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully!');
    }
}
