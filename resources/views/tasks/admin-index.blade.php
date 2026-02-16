<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:1.5rem;">
            <h2 style="font-size: 2rem; font-weight: 700; margin: 0;">Tasks Management</h2>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create Task
            </a>
        </div>
    </x-slot>

    <style>
        .project-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .project-card:hover {
            box-shadow: 0 6px 16px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        .project-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1a1a2e;
            margin: 0 0 0.5rem 0;
        }

        .project-info {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            color: #636e72;
            font-size: 0.95rem;
        }

        .project-info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tasks-list {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0,0,0,0.1);
        }

        .task-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .task-item:last-child {
            border-bottom: none;
        }

        .task-title {
            font-weight: 500;
            color: #212529;
        }

        .task-meta {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .badge-status {
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-todo {
            background: #e9ecef;
            color: #495057;
        }

        .badge-in_progress {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-in_review {
            background: #fff3cd;
            color: #856404;
        }

        .badge-done {
            background: #d4edda;
            color: #155724;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: #f8f9fa;
            border-radius: 12px;
            color: #636e72;
        }

        .empty-state i {
            font-size: 3rem;
            color: #adb5bd;
            margin-bottom: 1rem;
        }

        .collapse-toggle {
            background: none;
            border: none;
            cursor: pointer;
            color: #667eea;
            font-weight: 600;
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .collapse-toggle:hover {
            color: #764ba2;
        }

        .collapse-toggle .arrow {
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .collapse-toggle.collapsed .arrow {
            transform: rotate(-90deg);
        }
    </style>

    <div class="container-fluid px-4 px-lg-5 py-4">
        @if($projects->isEmpty())
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5>No Projects Yet</h5>
                <p>Create a project and add tasks to get started.</p>
                <a href="{{ route('projects.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus me-2"></i>Create Project
                </a>
            </div>
        @else
            @foreach($projects as $project)
                <div class="project-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="flex: 1;">
                            <h3 class="project-title">{{ $project->name }}</h3>
                            <div class="project-info">
                                <div class="project-info-item">
                                    <i class="fas fa-tasks"></i>
                                    <span><strong>{{ $project->tasks->count() }}</strong> tasks</span>
                                </div>
                                <div class="project-info-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span><strong>{{ $project->tasks->where('status', 'done')->count() }}</strong> completed</span>
                                </div>
                                <div class="project-info-item">
                                    <span class="badge" style="background: {{ $project->status === 'active' ? '#d1ecf1' : '#e9ecef' }}; color: {{ $project->status === 'active' ? '#0c5460' : '#495057' }};">
                                        {{ ucfirst($project->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-folder-open"></i>
                            </a>
                            <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" data-confirm-btn data-confirm-title="Delete Project" data-confirm-message="Are you sure you want to delete this project and all its tasks?">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($project->tasks->count() > 0)
                        <div class="tasks-list" id="tasks-{{ $project->id }}">
                            @php
                                // Group tasks by developer (assigned user) for this project
                                $groupedByDev = $project->tasks->groupBy(function ($t) {
                                    return $t->assignedUser?->name ?? 'Unassigned';
                                });
                            @endphp

                            @foreach($groupedByDev as $devName => $devTasks)
                                <div style="margin-bottom: 1rem;">
                                    <button class="collapse-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#tasks-{{ $project->id }}-dev-{{ \Illuminate\Support\Str::slug($devName) }}" aria-expanded="true">
                                        <span class="arrow">▼</span>
                                        <span>{{ $devName }}</span>
                                        <span style="font-size: 0.85rem; color: #adb5bd;">({{ $devTasks->count() }} tasks)</span>
                                    </button>

                                    <div id="tasks-{{ $project->id }}-dev-{{ \Illuminate\Support\Str::slug($devName) }}" class="collapse show" data-bs-parent="#tasks-{{ $project->id }}">
                                        @foreach($devTasks as $task)
                                            @php $status = $task->status; @endphp
                                            <div class="task-item">
                                                <div>
                                                    <div class="task-title">{{ $task->title }}</div>
                                                    <small style="color: #adb5bd;">{{ $task->description ? Str::limit($task->description, 60) : '—' }}</small>
                                                    <div style="margin-top: 0.3rem; font-size:0.8rem; color:#6c757d;">
                                                        <i class="fas fa-tag"></i> {{ ucfirst($task->category) }}
                                                    </div>
                                                </div>
                                                <div class="task-meta">
                                                    <span style="font-size: 0.85rem; color: #adb5bd;">
                                                        @if($task->deadline)
                                                            {{ $task->deadline->format('M d') }}
                                                        @else
                                                            —
                                                        @endif
                                                    </span>
                                                    <span class="badge-status badge-{{ $status }}">
                                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                    </span>
                                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 1rem; color: #adb5bd;">
                            <small>No tasks in this project</small>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</x-app-layout>
