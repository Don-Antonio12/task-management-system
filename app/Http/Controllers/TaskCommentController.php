<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller
{
    /**
     * Store a new comment or reply on a task.
     * Top-level comment (no parent_id): admin/customer only.
     * Reply (parent_id set): anyone who can view the task can reply.
     */
    public function store(Request $request, Task $task)
    {
        $user = Auth::user();
        $isAdminOrCustomer = $user->isAdminOrCustomer();
        $isAssignedDeveloper = $user->id === $task->assigned_to;
        $canView = $user->id === $task->user_id || $isAssignedDeveloper || $isAdminOrCustomer;

        if (!$canView) {
            abort(403, 'You cannot comment on this task.');
        }

        $validated = $request->validate([
            'body' => 'nullable|string|max:5000',
            'link' => 'nullable|url|max:2000',
            'parent_id' => 'nullable|exists:task_comments,id',
        ]);

        $parentId = $validated['parent_id'] ?? null;
        $body = trim($validated['body'] ?? '');
        $link = trim($validated['link'] ?? '');

        if ($body === '' && $link === '') {
            return back()->withErrors([
                'body' => 'Please provide a link or a message.',
            ])->withInput();
        }

        // Top-level comment:
        // - Admin/Customer can always add
        // - Assigned developer can also add (used as "submission bin")
        if (!$parentId) {
            if (!($isAdminOrCustomer || $isAssignedDeveloper)) {
                abort(403, 'You cannot start a new comment thread on this task. You can reply to existing comments.');
            }
        } else {
            // Reply: ensure parent belongs to this task
            $parent = TaskComment::where('id', $parentId)->where('task_id', $task->id)->firstOrFail();
        }

        // Build final body including optional link (used as submission bin for developers)
        $bodyToStore = $body;
        if ($link !== '') {
            $prefix = 'Link: ' . $link;
            $bodyToStore = $bodyToStore !== '' ? ($prefix . "\n\n" . $bodyToStore) : $prefix;
        }

        $comment = TaskComment::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'body' => $bodyToStore,
            'parent_id' => $parentId,
        ]);

        // Create notifications
        $recipients = collect();

        // 1) If reply, notify parent comment owner (except self)
        if (isset($parent) && $parent->user_id !== $user->id) {
            $recipients->push($parent->user);
            Notification::create([
                'user_id' => $parent->user_id,
                'type' => 'comment_reply',
                'project_id' => $task->project_id,
                'task_id' => $task->id,
                'message' => $user->getDisplayName() . ' replied to your comment on task "' . $task->title . '".',
            ]);
        }

        // 2) New top-level comment from admin/customer: notify assigned developer (if any)
        if (!$parentId && $isAdminOrCustomer && $task->assigned_to && $task->assigned_to !== $user->id) {
            Notification::create([
                'user_id' => $task->assigned_to,
                'type' => 'task_comment',
                'project_id' => $task->project_id,
                'task_id' => $task->id,
                'message' => 'New comment from ' . $user->getDisplayName() . ' on task "' . $task->title . '".',
            ]);
        }

        // 3) New top-level comment from assigned developer: notify all admins/customers
        if (!$parentId && $isAssignedDeveloper) {
            $admins = User::whereIn('role', ['admin', 'customer'])->get();
            foreach ($admins as $admin) {
                if ($admin->id === $user->id) {
                    continue;
                }
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'task_comment',
                    'project_id' => $task->project_id,
                    'task_id' => $task->id,
                    'message' => $user->getDisplayName() . ' commented on task "' . $task->title . '".',
                ]);
            }
        }

        return redirect()->route('tasks.show', $task)->with('success', $parentId ? 'Reply added.' : 'Comment added.');
    }
}
