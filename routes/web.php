<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Developer role pages (backend, frontend, server)
Route::get('/developer/{role}', [DeveloperController::class, 'index'])->middleware(['auth', 'verified'])->name('developer.index');
Route::get('/developer/{role}/projects', [DeveloperController::class, 'projects'])->middleware(['auth', 'verified'])->name('developer.projects');
Route::get('/developer/{role}/tasks', [DeveloperController::class, 'tasks'])->middleware(['auth', 'verified'])->name('developer.tasks');
Route::post('/developer/{role}/projects/{project}/submission', [DeveloperController::class, 'submitProjectLink'])->middleware(['auth', 'verified'])->name('developer.projects.submission');

Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tasks', TaskController::class);
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::post('tasks/{task}/comments', [TaskCommentController::class, 'store'])->name('tasks.comments.store');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    
    // Project routes
    Route::resource('projects', ProjectController::class);
    Route::get('projects/{project}/tasks/create', [ProjectController::class, 'createTasks'])->name('projects.tasks.create');
    Route::post('projects/{project}/tasks', [ProjectController::class, 'storeTasks'])->name('projects.tasks.store');
});

require __DIR__.'/auth.php';
