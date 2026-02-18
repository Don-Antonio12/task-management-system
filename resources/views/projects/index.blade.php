<x-app-layout>
    <x-slot name="header">
        <div class="projects-header">
            <h2 class="projects-title">Projects</h2>
            <a href="{{ route('projects.create') }}" class="btn btn-primary btn-create" style="background: #764ba2; color: white; border: none;">
                <i class="fas fa-plus me-2"></i>New Project
            </a>
        </div>
    </x-slot>

    <style>
        .projects-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .projects-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            color: #1a1d21;
        }
        .btn-create {
            border-radius: 10px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(52, 4, 71, 0.35);
        }
        .projects-page { padding: 0 0 2.5rem; }
        .projects-empty {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid rgba(29, 129, 159, 0.2);
            border-radius: 14px;
            padding: 2rem;
        }
        .projects-empty a { font-weight: 600; color: #0d6efd; }
        .project-card {
            height: 100%;
            border: 1px solid #e8ecf0;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
            display: flex;
            flex-direction: column;
        }
        .project-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        .project-card-accent {
            height: 4px;
            background: linear-gradient(135deg,rgb(37, 4, 39) 0%, #764ba2 100%);
        }
        .project-card-body { padding: 1.5rem; display: flex; flex-direction: column; flex: 1; }
        .project-card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color:rgb(0, 0, 0);
            margin-bottom: 0.5rem;
            line-height: 1.35;
        }
        .project-card-desc {
            font-size: 0.9rem;
            color:rgb(0, 0, 0);
            line-height: 1.5;
            margin-bottom: 1rem;
            min-height: 2.6em;
        }
        .project-card-section {
            margin-bottom: 1rem;
        }
        .project-card-section:last-of-type { margin-bottom: 0; }
        .project-stats {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            font-size: 0.85rem;
            color:rgb(0, 0, 0);
        }
        .project-stats-item {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .project-stats-item i { color: #764ba2; opacity: 0.9; }
        .project-roles-wrap { padding: 0.5rem 0; }
        .project-progress-wrap {
            margin-bottom: 1rem;
        }
        .project-progress-label {
            font-size: 0.75rem;
            color:rgb(0, 0, 0);
            margin-bottom: 0.35rem;
        }
        .project-progress {
            height: 6px;
            border-radius: 3px;
            background: #e8ecf0;
            overflow: hidden;
        }
        .project-progress-bar {
            height: 100%;
            border-radius: 3px;
            background: linear-gradient(135deg,rgb(37, 4, 39) 0%, #764ba2 100%);
            transition: width 0.4s ease;
        }
        .project-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-top: 0.75rem;
            border-top: 1px solid #e8ecf0;
        }
        .project-actions .btn-view {
            flex: 1;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            background: #ffffff;
            color: white;
            background: black;
            border: none;
        }
        .project-actions .btn-view:hover {
            background: #6d6d6d;
            color: white;
        }
        .project-actions .btn-icon {
            width: 38px;
            height: 38px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
        }
        .role-badge.assigned { color: #764ba2; background: #d1e7dd; }
        .role-badge.unassigned { color: #6c757d; background: #e9ecef; }
        .project-details-wrap {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            margin-bottom: 1rem;
            padding: 0.8rem;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 0.85rem;
        }
        .project-detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .project-detail-item i {
            color: #764ba2;
            width: 16px;
            text-align: center;
        }
        .project-detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 80px;
        }
        .project-detail-value {
            color: #1a1d21;
        }
        .priority-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.6rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        .priority-low { background: #d1e7dd; color: #0f5132; }
        .priority-medium { background: #fff3cd; color: #664d01; }
        .priority-high { background: #f8d7da; color: #842029; }
        .priority-urgent { background: #f69c6c; color: #fff; }
    </style>

    <div class="projects-page">
        @if ($projects->isEmpty())
            <div class="alert alert-info alert-dismissible fade show projects-empty" role="alert">
                <i class="fas fa-info-circle me-2"></i>No projects yet. <a href="{{ route('projects.create') }}">Create your first project</a>.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @else
            <div class="row g-4">
                @foreach ($projects as $project)
                    @php
                        $totalTasks = $project->tasks->count();
                        $completedCount = $project->tasks->where('status', 'done')->count();
                        $progressPercent = $totalTasks > 0 ? round(($completedCount / $totalTasks) * 100) : 0;
                    @endphp
                    <div class="col-sm-6 col-lg-4">
                        <div class="project-card">
                            <div class="project-card-accent"></div>
                            <div class="project-card-body">
                                <h3 class="project-card-title">{{ $project->name }}</h3>
                                <p class="project-card-desc">{{ Str::limit($project->description, 100) ?: 'No description.' }}</p>

                                <div class="project-details-wrap">
                                    <div class="project-detail-item">
                                        <i class="fas fa-flag"></i>
                                        <span class="project-detail-label">Priority:</span>
                                        <span class="priority-badge priority-{{ $project->priority ?? 'medium' }}">
                                            {{ ucfirst($project->priority ?? 'Medium') }}
                                        </span>
                                    </div>
                                    @if ($project->start_date)
                                    <div class="project-detail-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span class="project-detail-label">Start:</span>
                                        <span class="project-detail-value">{{ $project->start_date->format('M d, Y') }}</span>
                                    </div>
                                    @endif
                                    @if ($project->deadline)
                                    <div class="project-detail-item">
                                        <i class="fas fa-calendar-check"></i>
                                        <span class="project-detail-label">Deadline:</span>
                                        <span class="project-detail-value">{{ $project->deadline->format('M d, Y') }}</span>
                                    </div>
                                    @endif
                                </div>

                                <div class="project-card-section">
                                    <div class="project-stats">
                                        <span class="project-stats-item">
                                            <i class="fas fa-tasks"></i>
                                            <strong>{{ $totalTasks }}</strong> tasks
                                        </span>
                                        <span class="project-stats-item">
                                            <i class="fas fa-check-circle"></i>
                                            <strong>{{ $completedCount }}</strong> done
                                        </span>
                                    </div>
                                </div>

                                @php
                                    $backendAssigned = $project->tasks->filter(fn($t) => $t->category === 'backend' && $t->assigned_to)->map(fn($t) => $t->assignedUser?->name)->unique()->filter()->values();
                                    $frontendAssigned = $project->tasks->filter(fn($t) => $t->category === 'frontend' && $t->assigned_to)->map(fn($t) => $t->assignedUser?->name)->unique()->filter()->values();
                                    $serverAssigned = $project->tasks->filter(fn($t) => $t->category === 'server' && $t->assigned_to)->map(fn($t) => $t->assignedUser?->name)->unique()->filter()->values();
                                @endphp
                                <div class="project-card-section project-roles-wrap">
                                    <div class="project-progress-label" style="margin-bottom:0.4rem;">Assignments</div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="role-badge {{ $backendAssigned->isNotEmpty() ? 'assigned' : 'unassigned' }}" title="{{ $backendAssigned->isNotEmpty() ? 'Backend: ' . $backendAssigned->implode(', ') : 'Backend: Not assigned' }}">
                                            <i class="fas fa-{{ $backendAssigned->isNotEmpty() ? 'check-circle' : 'minus-circle' }}"></i> Backend
                                        </span>
                                        <span class="role-badge {{ $frontendAssigned->isNotEmpty() ? 'assigned' : 'unassigned' }}" title="{{ $frontendAssigned->isNotEmpty() ? 'Frontend: ' . $frontendAssigned->implode(', ') : 'Frontend: Not assigned' }}">
                                            <i class="fas fa-{{ $frontendAssigned->isNotEmpty() ? 'check-circle' : 'minus-circle' }}"></i> Frontend
                                        </span>
                                        <span class="role-badge {{ $serverAssigned->isNotEmpty() ? 'assigned' : 'unassigned' }}" title="{{ $serverAssigned->isNotEmpty() ? 'Server: ' . $serverAssigned->implode(', ') : 'Server: Not assigned' }}">
                                            <i class="fas fa-{{ $serverAssigned->isNotEmpty() ? 'check-circle' : 'minus-circle' }}"></i> Server
                                        </span>
                                    </div>
                                </div>

                                <div class="project-card-section project-progress-wrap">
                                    <div class="project-progress-label">{{ $progressPercent }}% complete</div>
                                    <div class="project-progress">
                                        <div class="project-progress-bar" role="progressbar" style="width: {{ $progressPercent }}%;"></div>
                                    </div>
                                </div>

                                <div class="project-actions" style="margin-top: auto;">
                                    <a href="{{ route('projects.show', $project) }}" class="btn btn-primary btn-view">
                                        <i class="fas fa-folder-open me-1"></i>View
                                    </a>
                                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-secondary btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-outline-danger btn-icon" title="Delete" data-confirm-btn data-confirm-title="Delete Project" data-confirm-message="Are you sure you want to delete this project?">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
