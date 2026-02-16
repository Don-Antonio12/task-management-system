<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create pre-defined users with roles
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => 'password',
        ]);

        $backend = User::factory()->create([
            'name' => 'Backend Dev',
            'email' => 'backend@example.com',
            'role' => 'backend',
            'password' => 'password',
        ]);

        $frontend = User::factory()->create([
            'name' => 'Frontend Dev',
            'email' => 'frontend@example.com',
            'role' => 'frontend',
            'password' => 'password',
        ]);

        $server = User::factory()->create([
            'name' => 'Server Admin',
            'email' => 'server@example.com',
            'role' => 'server',
            'password' => 'password',
        ]);

        // Create example tasks assigned by admin to backend/frontend/server
        \App\Models\Task::create([
            'user_id' => $admin->id,
            'title' => 'Implement API endpoints',
            'description' => 'Create endpoints for user management',
            'deadline' => now()->addDays(7),
            'assigned_to' => $backend->id,
            'status' => 'todo',
            'category' => 'backend',
        ]);

        \App\Models\Task::create([
            'user_id' => $admin->id,
            'title' => 'Build UI components',
            'description' => 'Create reusable components for dashboard',
            'deadline' => now()->addDays(5),
            'assigned_to' => $frontend->id,
            'status' => 'in_progress',
            'category' => 'frontend',
        ]);

        \App\Models\Task::create([
            'user_id' => $admin->id,
            'title' => 'Configure production server',
            'description' => 'Set up Nginx and deploy the app',
            'deadline' => now()->addDays(10),
            'assigned_to' => $server->id,
            'status' => 'todo',
            'category' => 'server',
        ]);
    }
}
