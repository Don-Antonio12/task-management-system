<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the tasks created by the user
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the tasks assigned to the user
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    /**
     * Whether the user has admin or customer role (dashboard / projects / full task access).
     */
    public function isAdminOrCustomer(): bool
    {
        return in_array($this->role, ['admin', 'customer']);
    }

    /**
     * Display name for the current viewer. Developers see "Customer" instead of the real name for customers.
     */
    public function getDisplayName(): string
    {
        $viewer = auth()->user();
        if ($viewer && !$viewer->isAdminOrCustomer() && $this->role === 'customer') {
            return 'Customer';
        }
        return $this->name;
    }
}
