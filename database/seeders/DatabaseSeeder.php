<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            array_merge(User::factory()->definition(), [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'password' => $password,
            ])
        );
        $admin->update(['password' => $password, 'role' => 'admin', 'name' => 'Admin User']);

        // 4 Backend developers
        $backends = [];
        foreach (['Backend Dev 1', 'Backend Dev 2', 'Backend Dev 3', 'Backend Dev 4'] as $i => $name) {
            $email = 'backend' . ($i + 1) . '@example.com';
            $u = User::firstOrCreate(
                ['email' => $email],
                array_merge(User::factory()->definition(), [
                    'name' => $name,
                    'email' => $email,
                    'role' => 'backend',
                    'password' => $password,
                ])
            );
            $u->update(['password' => $password, 'role' => 'backend', 'name' => $name]);
            $backends[] = $u;
        }

        // 4 Frontend developers
        $frontends = [];
        foreach (['Frontend Dev 1', 'Frontend Dev 2', 'Frontend Dev 3', 'Frontend Dev 4'] as $i => $name) {
            $email = 'frontend' . ($i + 1) . '@example.com';
            $u = User::firstOrCreate(
                ['email' => $email],
                array_merge(User::factory()->definition(), [
                    'name' => $name,
                    'email' => $email,
                    'role' => 'frontend',
                    'password' => $password,
                ])
            );
            $u->update(['password' => $password, 'role' => 'frontend', 'name' => $name]);
            $frontends[] = $u;
        }

        // 5 Server admins
        $servers = [];
        foreach (['Server Admin 1', 'Server Admin 2', 'Server Admin 3', 'Server Admin 4', 'Server Admin 5'] as $i => $name) {
            $email = 'server' . ($i + 1) . '@example.com';
            $u = User::firstOrCreate(
                ['email' => $email],
                array_merge(User::factory()->definition(), [
                    'name' => $name,
                    'email' => $email,
                    'role' => 'server',
                    'password' => $password,
                ])
            );
            $u->update(['password' => $password, 'role' => 'server', 'name' => $name]);
            $servers[] = $u;
        }

        // Example tasks assigned by admin
        \App\Models\Task::create([
            'user_id' => $admin->id,
            'title' => 'Implement API endpoints',
            'description' => 'Create endpoints for user management',
            'deadline' => now()->addDays(7),
            'assigned_to' => $backends[0]->id,
            'status' => 'todo',
            'category' => 'backend',
        ]);

        \App\Models\Task::create([
            'user_id' => $admin->id,
            'title' => 'Build UI components',
            'description' => 'Create reusable components for dashboard',
            'deadline' => now()->addDays(5),
            'assigned_to' => $frontends[0]->id,
            'status' => 'in_progress',
            'category' => 'frontend',
        ]);

        \App\Models\Task::create([
            'user_id' => $admin->id,
            'title' => 'Configure production server',
            'description' => 'Set up Nginx and deploy the app',
            'deadline' => now()->addDays(10),
            'assigned_to' => $servers[0]->id,
            'status' => 'todo',
            'category' => 'server',
        ]);
    }
}
