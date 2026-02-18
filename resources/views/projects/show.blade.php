<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; padding: 1.5rem 2rem; border-radius: 12px; color: #000000; border: 1px solid #000000;">
            <div>
                <h1 style="margin:0; font-size:2rem; font-weight:700;">
                    <i class="fas fa-folder me-2"></i>{{ $project->name }}
                </h1>
                <p style="margin: 0.5rem 0 0 0; opacity: 0.9; font-size: 0.95rem;">{{ $project->description }}</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ Auth::user()->isAdminOrCustomer() ? route('projects.index') : route('developer.projects', Auth::user()->role) }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
                @if(Auth::user()->isAdminOrCustomer())
                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-light">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="container-fluid px-4 px-lg-5 py-5" style="border: 1px solid #000000;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h3 style="margin: 0; font-size: 1.3rem; font-weight: 600;">Tasks</h3>
                <p style="margin: 0.5rem 0 0; color: #636e72; font-size: 0.9rem;">
                    @php
                        $categoryLabel = $category ? ucfirst($category) . ' tasks' : 'All categories';
                    @endphp
                    {{ $tasks->count() }} tasks &middot; {{ $categoryLabel }}
                </p>
                @if(Auth::user()->isAdminOrCustomer())
                    @php
                        $be = $tasks->filter(fn($t) => $t->category === 'backend' && $t->assigned_to)->map(fn($t) => $t->assignedUser?->name)->unique()->filter()->values();
                        $fe = $tasks->filter(fn($t) => $t->category === 'frontend' && $t->assigned_to)->map(fn($t) => $t->assignedUser?->name)->unique()->filter()->values();
                        $sv = $tasks->filter(fn($t) => $t->category === 'server' && $t->assigned_to)->map(fn($t) => $t->assignedUser?->name)->unique()->filter()->values();
                    @endphp
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;">
                        <span style="font-size:0.8rem; padding:0.2rem 0.5rem; border-radius:6px; {{ $be->isNotEmpty() ? 'background:#d1e7dd; color:#198754' : 'background:#e9ecef; color:#6c757d' }}" title="{{ $be->isNotEmpty() ? 'Backend: ' . $be->implode(', ') : 'Backend: Not assigned' }}"><i class="fas fa-{{ $be->isNotEmpty() ? 'check-circle' : 'minus-circle' }}"></i> Backend</span>
                        <span style="font-size:0.8rem; padding:0.2rem 0.5rem; border-radius:6px; {{ $fe->isNotEmpty() ? 'background:#d1e7dd; color:#198754' : 'background:#e9ecef; color:#6c757d' }}" title="{{ $fe->isNotEmpty() ? 'Frontend: ' . $fe->implode(', ') : 'Frontend: Not assigned' }}"><i class="fas fa-{{ $fe->isNotEmpty() ? 'check-circle' : 'minus-circle' }}"></i> Frontend</span>
                        <span style="font-size:0.8rem; padding:0.2rem 0.5rem; border-radius:6px; {{ $sv->isNotEmpty() ? 'background:#d1e7dd; color:#198754' : 'background:#e9ecef; color:#6c757d' }}" title="{{ $sv->isNotEmpty() ? 'Server: ' . $sv->implode(', ') : 'Server: Not assigned' }}"><i class="fas fa-{{ $sv->isNotEmpty() ? 'check-circle' : 'minus-circle' }}"></i> Server</span>
                    </div>

                    @php
                        $backendLink = $project->backend_submission_link ?? null;
                        $frontendLink = $project->frontend_submission_link ?? null;
                        $serverLink = $project->server_submission_link ?? null;
                    @endphp
                    <div style="margin-top:0.75rem; display:flex; flex-direction:column; gap:0.25rem; font-size:0.85rem;">
                        <div>
                            <strong><i class="fas fa-link me-1"></i>Backend submission:</strong>
                            @if($backendLink)
                                <a href="{{ $backendLink }}" target="_blank" rel="noopener noreferrer">{{ \Illuminate\Support\Str::limit($backendLink, 60) }}</a>
                            @else
                                <span style="color:#adb5bd;">No submission yet</span>
                            @endif
                        </div>
                        <div>
                            <strong><i class="fas fa-link me-1"></i>Frontend submission:</strong>
                            @if($frontendLink)
                                <a href="{{ $frontendLink }}" target="_blank" rel="noopener noreferrer">{{ \Illuminate\Support\Str::limit($frontendLink, 60) }}</a>
                            @else
                                <span style="color:#adb5bd;">No submission yet</span>
                            @endif
                        </div>
                        <div>
                            <strong><i class="fas fa-link me-1"></i>Server submission:</strong>
                            @if($serverLink)
                                <a href="{{ $serverLink }}" target="_blank" rel="noopener noreferrer">{{ \Illuminate\Support\Str::limit($serverLink, 60) }}</a>
                            @else
                                <span style="color:#adb5bd;">No submission yet</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            <div style="display:flex; flex-direction:column; gap:0.75rem; align-items:flex-end;">
                @if(Auth::user()->isAdminOrCustomer())
                    @php
                        $cat = $category;
                        $allCount = $project->tasks()->count();
                        $backendCount = $project->tasks()->where('category', 'backend')->count();
                        $frontendCount = $project->tasks()->where('category', 'frontend')->count();
                        $serverCount = $project->tasks()->where('category', 'server')->count();
                    @endphp
                    <div class="category-filter" role="group" aria-label="Filter tasks by category">
                        <a href="{{ route('projects.show', $project) }}" class="category-filter-btn {{ $cat === null ? 'active' : '' }}">
                            <i class="fas fa-th-large"></i>
                            <span>All</span>
                            <span class="category-filter-count">{{ $allCount }}</span>
                        </a>
                        <a href="{{ route('projects.show', [$project, 'category' => 'backend']) }}" class="category-filter-btn {{ $cat === 'backend' ? 'active' : '' }}">
                            <i class="fas fa-code"></i>
                            <span>Backend</span>
                            <span class="category-filter-count">{{ $backendCount }}</span>
                        </a>
                        <a href="{{ route('projects.show', [$project, 'category' => 'frontend']) }}" class="category-filter-btn {{ $cat === 'frontend' ? 'active' : '' }}">
                            <i class="fas fa-palette"></i>
                            <span>Frontend</span>
                            <span class="category-filter-count">{{ $frontendCount }}</span>
                        </a>
                        <a href="{{ route('projects.show', [$project, 'category' => 'server']) }}" class="category-filter-btn {{ $cat === 'server' ? 'active' : '' }}">
                            <i class="fas fa-server"></i>
                            <span>Server</span>
                            <span class="category-filter-count">{{ $serverCount }}</span>
                        </a>
                    </div>
                    <a href="{{ route('projects.tasks.create', $project) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add tasks
                    </a>
                @endif
            </div>
        </div>

        <style>
            .category-filter {
                display: inline-flex;
                flex-wrap: wrap;
                gap: 0.35rem;
                padding: 0.35rem;
                background: #f1f3f5;
                border-radius: 12px;
                border: 1px solid #e9ecef;
                box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            }
            .category-filter-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                border-radius: 8px;
                font-size: 0.9rem;
                font-weight: 600;
                color: #495057;
                text-decoration: none;
                transition: all 0.2s ease;
                border: 1px solid transparent;
            }
            .category-filter-btn i {
                font-size: 0.95rem;
                opacity: 0.9;
            }
            .category-filter-btn:hover {
                background: #fff;
                color: #1D809F;
                border-color: #dee2e6;
                box-shadow: 0 2px 6px rgba(0,0,0,0.06);
            }
            .category-filter-btn.active {
                background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
                color: #fff;
                border-color: transparent;
                box-shadow: 0 2px 8px rgba(118, 75, 162, 0.35);
            }
            .category-filter-btn.active:hover {
                background: linear-gradient(135deg, #5f3d82 0%, #5568d3 100%);
                color: #fff;
                box-shadow: 0 3px 10px rgba(118, 75, 162, 0.4);
            }
            .category-filter-count {
                min-width: 1.5rem;
                padding: 0.15rem 0.4rem;
                border-radius: 6px;
                font-size: 0.75rem;
                font-weight: 700;
                background: rgba(0,0,0,0.08);
                color: inherit;
            }
            .category-filter-btn.active .category-filter-count {
                background: rgba(255,255,255,0.25);
            }
            @media (max-width: 576px) {
                .category-filter { width: 100%; justify-content: center; }
                .category-filter-btn { flex: 1; min-width: 0; justify-content: center; }
                .category-filter-btn span:not(.category-filter-count) { display: none; }
            }

            .board { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; }
            .column { background: #f8f9fa; border-radius: 12px; padding: 1rem; min-height: 420px; border: 1px solid #e9ecef; }
            .column h4 { margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.95rem; font-weight: 700; color: #1a1d21; padding-bottom: 0.75rem; border-bottom: 2px solid #e9ecef; }
            .column-todo h4 { border-bottom-color: #6c757d; }
            .column-in_progress h4 { border-bottom-color: #0dcaf0; }
            .column-in_review h4 { border-bottom-color: #ffc107; }
            .column-done h4 { border-bottom-color: #198754; }
            .column h4 .col-count { margin-left: auto; background: #e9ecef; color: #6c757d; padding: 0.2rem 0.5rem; border-radius: 8px; font-size: 0.8rem; }
            .task-card { background: #f8f9fa; border-radius: 12px; padding: 1.1rem; margin-bottom: 1rem; box-shadow: 0 1px 4px rgba(0,0,0,0.06); border: 1px solid #e9ecef; transition: all 0.2s; }
            .task-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
            .task-card-header { display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1rem; }
            .task-card-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; flex-shrink: 0; background: #dee2e6; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 600; color: #495057; overflow: hidden; }
            .task-card-avatar img { width: 100%; height: 100%; object-fit: cover; }
            .task-card-assigned { flex: 1; min-width: 0; font-size: 0.85rem; color: #495057; }
            .task-card-assigned .label { color: #6c757d; font-weight: 500; }
            .task-card-section { margin-bottom: 0.75rem; }
            .task-card-section:last-of-type { margin-bottom: 0; }
            .task-card-section-label { font-size: 0.75rem; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 0.25rem; }
            .task-card .task-title { font-weight: 700; font-size: 0.95rem; color: #1a1d21; display: block; text-decoration: none; line-height: 1.3; }
            .task-card .task-title:hover { color: #1D809F; }
            .task-card .task-description { font-size: 0.85rem; color: #495057; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
            .task-card .task-comment { font-size: 0.8rem; color: #6c757d; line-height: 1.35; padding: 0.5rem; background: #fff; border-radius: 6px; border-left: 3px solid #1D809F; }
            .task-card .task-comment-empty { font-style: italic; color: #adb5bd; }
            .task-deadline { font-size: 0.85rem; color: #495057; display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; }
            .task-deadline i { color: #6c757d; font-size: 0.8rem; }
            .task-deadline.overdue { color: #dc3545; }
            .task-deadline.overdue i { color: #dc3545; }
            .task-deadline .badge-overdue { font-size: 0.7rem; font-weight: 600; padding: 0.15rem 0.4rem; border-radius: 4px; background: #dc3545; color: #fff; }
            .task-deadline.empty { color: #adb5bd; font-style: italic; }
            .task-card-footer { margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid #e9ecef; display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; justify-content: space-between; }
            .task-card .status-select { font-size: 0.85rem; padding: 0.4rem 1.75rem 0.4rem 0.6rem; border-radius: 8px; border: 1px solid #dee2e6; min-width: 120px; background: #fff; cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%236c757d' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.5rem center; }
            .task-card .btn-link { font-size: 0.8rem; padding: 0.35rem 0.6rem; }
            @media (max-width: 1200px) { .board { grid-template-columns: repeat(2, 1fr); } }
            @media (max-width: 768px) { .board { grid-template-columns: 1fr; } }
        </style>

        <div class="board">
            @php
                $statusConfig = [
                    'todo' => ['label' => 'To Do', 'icon' => 'inbox'],
                    'in_progress' => ['label' => 'In Progress', 'icon' => 'spinner'],
                    'in_review' => ['label' => 'In Review', 'icon' => 'eye'],
                    'done' => ['label' => 'Done', 'icon' => 'check-circle'],
                ];
            @endphp

            @foreach($statusConfig as $statusKey => $cfg)
                <div class="column column-{{ $statusKey }}" data-status="{{ $statusKey }}">
                    <h4><i class="fas fa-{{ $cfg['icon'] }}"></i> {{ $cfg['label'] }} <span class="col-count">{{ count($grouped[$statusKey] ?? []) }}</span></h4>

                    @forelse($grouped[$statusKey] ?? [] as $task)
                        @php
                            $assignee = $task->assignedUser;
                            $latestComment = $task->comments->first();
                            $statusLabels = ['todo' => 'To Do', 'in_progress' => 'In Progress', 'in_review' => 'In Review', 'done' => 'Done'];
                        @endphp
                        <div class="task-card" data-task-id="{{ $task->id }}">
                            <div class="task-card-header">
                                <div class="task-card-avatar">
                                    @if($assignee && $assignee->profile_photo_url)
                                        <img src="{{ $assignee->profile_photo_url }}" alt="">
                                    @else
                                        {{ $assignee ? strtoupper(mb_substr($assignee->getDisplayName(), 0, 1)) : '?' }}
                                    @endif
                                </div>
                                <div class="task-card-assigned">
                                    <span class="label">Name:</span> {{ $assignee ? $assignee->getDisplayName() : 'Unassigned' }}
                                </div>
                            </div>

                            <div class="task-card-section">
                                <div class="task-card-section-label">Task Name:</div>
                                <a href="{{ route('tasks.show', $task) }}" class="task-title">{{ $task->title }}</a>
                            </div>

                            <div class="task-card-section">
                                <div class="task-card-section-label">Description:</div>
                                <div class="task-description">{{ $task->description ? Str::limit($task->description, 80) : '—' }}</div>
                            </div>

                            <div class="task-card-section">
                                <div class="task-card-section-label">Deadline:</div>
                                @if($task->deadline)
                                    @php $isOverdue = $task->deadline->isPast() && $task->status !== 'done'; @endphp
                                    <div class="task-deadline {{ $isOverdue ? 'overdue' : '' }}">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $task->deadline->format('M d, Y') }}
                                        @if($isOverdue)
                                            <span class="badge-overdue">Overdue</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="task-deadline empty">—</div>
                                @endif
                            </div>

                            <div class="task-card-section">
                                <div class="task-card-section-label">Comment:</div>
                                @if($latestComment)
                                    <div class="task-comment">
                                        <span style="font-weight:600; color:#1D809F;">{{ $latestComment->user->getDisplayName() }}:</span>
                                        @php $body = $latestComment->body; $previewLen = 50; $showMore = strlen($body) > $previewLen; @endphp
                                        {{ $showMore ? Str::limit($body, $previewLen) : $body }}
                                        @if($showMore) <a href="{{ route('tasks.show', $task) }}#comments" style="font-weight:600; color:#1D809F;">See more</a> @endif
                                    </div>
                                @else
                                    <div class="task-comment task-comment-empty"><a href="{{ route('tasks.show', $task) }}#comments" style="color:#1D809F;">Add comment</a></div>
                                @endif
                            </div>

                            <div class="task-card-footer">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    @if(Auth::user()->id === $task->assigned_to || Auth::user()->isAdminOrCustomer())
                                        <select class="status-select" data-task="{{ $task->id }}">
                                            @foreach($statusLabels as $val => $label)
                                                <option value="{{ $val }}" {{ $task->status == $val ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <span style="font-size:0.85rem; color:#6c757d;">{{ $statusLabels[$task->status] ?? $task->status }}</span>
                                    @endif
                                </div>
                                <div style="display: flex; gap: 0.35rem;">
                                    <a href="{{ route('tasks.show', $task) }}#comments" class="btn btn-sm btn-outline-secondary btn-link" title="Comments"><i class="fas fa-comments"></i></a>
                                    @if(Auth::user()->isAdminOrCustomer())
                                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary btn-link" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-link" title="Delete" data-confirm-btn data-confirm-title="Delete Task" data-confirm-message="Are you sure you want to delete this task?"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="color:#adb5bd; font-style:italic; padding:1rem;">No tasks</p>
                    @endforelse
                </div>
            @endforeach
        </div>
    </div>

    <script>
        (function() {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            document.querySelectorAll('.status-select').forEach(function(sel) {
                sel.addEventListener('change', async function() {
                    const taskId = this.getAttribute('data-task');
                    const status = this.value;
                    const card = this.closest('.task-card');
                    if (!taskId || !status) return;
                    this.disabled = true;
                    try {
                        const resp = await fetch('/tasks/' + taskId + '/status', {
                            method: 'PATCH',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                            body: JSON.stringify({ status })
                        });
                        if (!resp.ok) throw new Error('HTTP ' + resp.status);
                        const data = await resp.json();
                        if (!data.success) throw new Error(data.message || 'Failed');
                        const target = document.querySelector('.column-' + status);
                        if (target) target.appendChild(card);
                        document.querySelectorAll('.column').forEach(function(col) {
                            const span = col.querySelector('h4 .col-count');
                            if (span) span.textContent = col.querySelectorAll('.task-card').length;
                        });
                    } catch (err) {
                        console.error(err);
                        this.value = card.closest('.column')?.className.match(/column-(\w+)/)?.[1] || 'todo';
                        (window.TMS && window.TMS.showErrorModal) ? TMS.showErrorModal('Failed to update: ' + (err.message || 'error')) : alert('Failed to update: ' + (err.message || 'error'));
                    } finally {
                        this.disabled = false;
                    }
                });
            });
        })();
    </script>
</x-app-layout>
