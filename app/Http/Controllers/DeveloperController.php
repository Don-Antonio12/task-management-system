<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Project;
use App\Models\Notification;
use App\Models\User;

class DeveloperController extends Controller
{
    public function index(Request $request, $role)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // allow admin to view any role page; otherwise ensure role matches
        if ($user->role !== 'admin' && $user->role !== $role) {
            abort(403);
        }

        // support optional project filter via ?project={id}
        $projectId = $request->query('project');

        // base tasks query: admin sees all in category; developer sees only tasks assigned to them (in their category)
        if ($user->role === 'admin') {
            $tasksQuery = Task::where('category', $role);
        } else {
            $tasksQuery = Task::where('assigned_to', $user->id)->where('category', $role);
        }

        if ($projectId) {
            $tasksQuery->where('project_id', $projectId);
        }

        $tasks = $tasksQuery->with('assignedUser', 'user', 'project')->get();

        // load projects that have tasks assigned to this user (or all projects for admin)
        if ($user->role === 'admin') {
            $projects = Project::withCount(['tasks as assigned_tasks_count' => function ($q) use ($role) {
                $q->where('category', $role);
            }])->get();
        } else {
            $projects = Project::whereHas('tasks', function ($q) use ($user) {
                $q->where('assigned_to', $user->id);
            })->withCount(['tasks as assigned_tasks_count' => function ($q) use ($user) {
                $q->where('assigned_to', $user->id);
            }])->get();
        }

        // compute per-project stats for dashboard (assigned totals, done counts, percent)
        $projects = $projects->map(function ($p) use ($user, $role) {
            if ($user->role === 'admin') {
                $total = $p->tasks()->where('category', $role)->count();
                $done = $p->tasks()->where('category', $role)->where('status', 'done')->count();
            } else {
                $total = $p->tasks()->where('assigned_to', $user->id)->count();
                $done = $p->tasks()->where('assigned_to', $user->id)->where('status', 'done')->count();
            }

            $p->assigned_total = $total;
            $p->done_count = $done;
            $p->percent_done = $total ? round(($done / $total) * 100) : 0;
            return $p;
        });

        return view('developers.index', compact('tasks', 'role', 'projects', 'projectId'));
    }

    // Projects listing page for developers
    public function projects(Request $request, $role)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->role !== 'admin' && $user->role !== $role) {
            abort(403);
        }

        if ($user->role === 'admin') {
            $projects = Project::withCount(['tasks as assigned_tasks_count' => function ($q) use ($role) {
                $q->where('category', $role);
            }])->get();
        } else {
            $projects = Project::whereHas('tasks', function ($q) use ($user) {
                $q->where('assigned_to', $user->id);
            })->withCount(['tasks as assigned_tasks_count' => function ($q) use ($user) {
                $q->where('assigned_to', $user->id);
            }])->get();
        }

        // compute stats for each project
        $projects = $projects->map(function ($p) use ($user, $role) {
            if ($user->role === 'admin') {
                $total = $p->tasks()->where('category', $role)->count();
                $done = $p->tasks()->where('category', $role)->where('status', 'done')->count();
            } else {
                $total = $p->tasks()->where('assigned_to', $user->id)->count();
                $done = $p->tasks()->where('assigned_to', $user->id)->where('status', 'done')->count();
            }
            $p->assigned_total = $total;
            $p->done_count = $done;
            $p->percent_done = $total ? round(($done / $total) * 100) : 0;
            return $p;
        });

        return view('developers.projects', compact('projects', 'role'));
    }

    // Tasks listing page for developers (separate from dashboard)
    public function tasks(Request $request, $role)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->role !== 'admin' && $user->role !== $role) {
            abort(403);
        }

        if ($user->role === 'admin') {
            $tasks = Task::where('category', $role)->with(['assignedUser', 'user', 'project', 'comments' => fn ($q) => $q->with('user')->orderBy('created_at', 'desc')])->get();
        } else {
            // Developers see ONLY tasks assigned to them in their category
            $tasks = Task::where('assigned_to', $user->id)->where('category', $role)->with(['assignedUser', 'user', 'project', 'comments' => fn ($q) => $q->with('user')->orderBy('created_at', 'desc')])->get();
        }

        return view('developers.tasks', compact('tasks', 'role'));
    }

    /**
     * Store / update submission link for a project card (per role).
     */
    public function submitProjectLink(Request $request, $role, Project $project)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        // Only admin or developer of that role can submit
        if ($user->role !== 'admin' && $user->role !== $role) {
            abort(403);
        }

        // Basic validation
        $validated = $request->validate([
            'submission_link' => 'nullable|url|max:2000',
        ]);

        $field = match ($role) {
            'backend' => 'backend_submission_link',
            'frontend' => 'frontend_submission_link',
            'server' => 'server_submission_link',
            default => null,
        };

        if (! $field) {
            abort(400, 'Invalid role for submission.');
        }

        $project->{$field} = $validated['submission_link'] ?: null;
        $project->save();

        // Notify all admins/customers that a submission was added/updated
        if ($validated['submission_link']) {
            $admins = User::whereIn('role', ['admin', 'customer'])->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'project_submission',
                    'project_id' => $project->id,
                    'task_id' => null,
                    'message' => ucfirst($role) . ' submission added for project "' . $project->name . '" by ' . $user->getDisplayName() . '.',
                ]);
            }
        }

        return back()->with('success', 'Submission link saved.');
    }
}
