<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'title',
        'description',
        'deadline',
        'assigned_to',
        'status',
        'order',
        'category',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /** Top-level comments (no parent). Replies are loaded via Comment->replies(). */
    public function comments()
    {
        return $this->hasMany(TaskComment::class, 'task_id')->whereNull('parent_id')->orderBy('created_at');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'in_review' => 'In Review',
            'done' => 'Done',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'todo' => 'secondary',
            'in_progress' => 'primary',
            'in_review' => 'warning',
            'done' => 'success',
        ];
        return $colors[$this->status] ?? 'secondary';
    }
}
