<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'status',
        'priority',
        'start_date',
        'deadline',
        'backend_assigned_to',
        'frontend_assigned_to',
        'server_assigned_to',
        'backend_submission_link',
        'frontend_submission_link',
        'server_submission_link',
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function backendDeveloper()
    {
        return $this->belongsTo(User::class, 'backend_assigned_to');
    }

    public function frontendDeveloper()
    {
        return $this->belongsTo(User::class, 'frontend_assigned_to');
    }

    public function serverDeveloper()
    {
        return $this->belongsTo(User::class, 'server_assigned_to');
    }

    /** Get assigned user id for a category (backend, frontend, server). */
    public function getAssignedToForCategory(string $category): ?int
    {
        return match ($category) {
            'backend' => $this->backend_assigned_to,
            'frontend' => $this->frontend_assigned_to,
            'server' => $this->server_assigned_to,
            default => null,
        };
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
