<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect only developers (backend/frontend/server) to role-specific page; admin and customer see main dashboard
        if ($user && isset($user->role) && !$user->isAdminOrCustomer()) {
            return redirect()->route('developer.index', ['role' => $user->role]);
        }

        // Get task statistics for the current user
        $totalTasks = Task::where('user_id', $user->id)->count();

        // Admins are treated like customers for visibility: show only projects they created
        if ($user->role === 'admin' || $user->role === 'customer') {
            $projects = Project::withCount([
                'tasks',
                'tasks as done_count' => function ($q) {
                    $q->where('status', 'done');
                },
                'tasks as in_progress_count' => function ($q) {
                    $q->where('status', 'in_progress');
                },
                'tasks as open_overdue_count' => function ($q) {
                    $q->where('status', '!=', 'done')
                      ->whereNotNull('deadline')
                      ->where('deadline', '<', now());
                },
            ])->where('created_by', $user->id)->orderBy('created_at', 'desc')->get();

            $totalProjects = $projects->count();
        } else {
            $projects = collect();
            $totalProjects = Project::where('created_by', $user->id)->count();
        }

        // Compute per-project percent_done and auto-complete logic for both admin and customer scopes
        foreach ($projects as $p) {
            $total = $p->tasks_count;
            $done = $p->done_count ?? 0;
            $percent = $total ? round(($done / $total) * 100) : 0;

            if ($percent === 100 && $total > 0 && $p->status !== 'completed') {
                $p->status = 'completed';
                $p->save();
            }
            $p->percent_done = $percent;
        }

        // project-level summary counts based on the currently loaded $projects
        $projectCompleted = $projects->filter(function ($p) {
            return $p->tasks_count > 0 && ($p->done_count ?? 0) === $p->tasks_count;
        })->count();

        $projectInProgress = $projects->filter(function ($p) {
            if ($p->tasks_count === 0) return false;
            if (($p->done_count ?? 0) === $p->tasks_count) return false;
            return ($p->in_progress_count ?? 0) > ($p->tasks_count / 2) || (($p->done_count ?? 0) > 0 && ($p->done_count ?? 0) < $p->tasks_count);
        })->count();

        $projectOverdue = $projects->filter(function ($p) {
            return ($p->open_overdue_count ?? 0) > 0;
        })->count();

        // Team counts should be visible to both admin and customer
        $backendCount = User::where('role', 'backend')->count();
        $frontendCount = User::where('role', 'frontend')->count();
        $serverCount = User::where('role', 'server')->count();
        $inProgressTasks = Task::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->count();
        $completedTasks = Task::where('user_id', $user->id)
            ->where('status', 'done')
            ->count();
        $overdueTasks = Task::where('user_id', $user->id)
            ->where('status', '!=', 'done')
            ->where('deadline', '<', now())
            ->count();
        
        return view('dashboard', [
            'totalTasks' => $totalTasks,
            'totalProjects' => $totalProjects,
            'projectInProgress' => $projectInProgress ?? 0,
            'projectCompleted' => $projectCompleted ?? 0,
            'projectOverdue' => $projectOverdue ?? 0,
            'inProgressTasks' => $inProgressTasks,
            'completedTasks' => $completedTasks,
            'overdueTasks' => $overdueTasks,
            'projects' => $projects ?? collect(),
            'backendCount' => $backendCount ?? 0,
            'frontendCount' => $frontendCount ?? 0,
            'serverCount' => $serverCount ?? 0,
        ]);
    }

    /**
     * Return dashboard overview data as JSON for live updates (polling).
     */
    public function overview()
    {
        $user = Auth::user();
        if ($user && isset($user->role) && !$user->isAdminOrCustomer()) {
            return response()->json(['message' => 'Not allowed'], 403);
        }

        // Overview JSON: admins treated like customers for visibility
        if ($user->role === 'admin' || $user->role === 'customer') {
            $totalProjects = Project::where('created_by', $user->id)->count();
            $projects = Project::with(['tasks'])->withCount([
                'tasks',
                'tasks as done_count' => function ($q) {
                    $q->where('status', 'done');
                },
                'tasks as in_progress_count' => function ($q) {
                    $q->where('status', 'in_progress');
                },
                'tasks as open_overdue_count' => function ($q) {
                    $q->where('status', '!=', 'done')
                      ->whereNotNull('deadline')
                      ->where('deadline', '<', now());
                },
            ])->where('created_by', $user->id)->orderBy('created_at', 'desc')->get();
        } else {
            // developers not allowed here (handled earlier), but keep empty
            $totalProjects = 0;
            $projects = collect();
        }

        foreach ($projects as $p) {
            $total = $p->tasks_count;
            $done = $p->done_count;
            $percent = $total ? round(($done / $total) * 100) : 0;
            if ($percent === 100 && $total > 0 && $p->status !== 'completed') {
                $p->status = 'completed';
                $p->save();
            }
            $p->percent_done = $percent;
        }

        $projectCompleted = $projects->filter(function ($p) {
            return $p->tasks_count > 0 && $p->done_count === $p->tasks_count;
        })->count();

        $projectInProgress = $projects->filter(function ($p) {
            if ($p->tasks_count === 0) return false;
            if ($p->done_count === $p->tasks_count) return false;
            return $p->in_progress_count > ($p->tasks_count / 2);
        })->count();

        $projectOverdue = $projects->filter(function ($p) {
            return $p->open_overdue_count > 0;
        })->count();

        $projectsPayload = $projects->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'status' => $p->status,
                'percent_done' => $p->percent_done,
                'tasks_count' => $p->tasks_count,
                'done_count' => $p->done_count,
                'todo' => $p->tasks->where('status', 'todo')->count(),
                'in_progress' => $p->tasks->where('status', 'in_progress')->count(),
                'in_review' => $p->tasks->where('status', 'in_review')->count(),
                'done' => $p->tasks->where('status', 'done')->count(),
            ];
        })->values();

        return response()->json([
            'totalProjects' => $totalProjects,
            'projectCompleted' => $projectCompleted,
            'projectInProgress' => $projectInProgress,
            'projectOverdue' => $projectOverdue,
            'projects' => $projectsPayload,
        ]);
    }
}
