<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->user_id || $user->id === $task->assigned_to;
    }

    /**
     * Determine whether the user can create tasks.
     */
    public function create(User $user): bool
    {
        // admin and customer can create tasks (admin-style page)
        return isset($user->role) && in_array($user->role, ['admin', 'customer']);
    }

    /**
     * Determine whether the user can update the task.
     * Only admin/customer (or creator) can edit; developers cannot edit tasks.
     */
    public function update(User $user, Task $task): bool
    {
        return in_array($user->role, ['admin', 'customer']) || $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can delete the task.
     * Only admin/customer (or creator) can delete; developers cannot delete tasks.
     */
    public function delete(User $user, Task $task): bool
    {
        return in_array($user->role, ['admin', 'customer']) || $user->id === $task->user_id;
    }
}
