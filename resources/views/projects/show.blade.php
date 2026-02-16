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
            <div style="display:flex; flex-direction:column; gap:0.5rem; align-items:flex-end;">
                @if(Auth::user()->isAdminOrCustomer())
                    <div class="btn-group" role="group" aria-label="Category filter">
                        @php
                            $cat = $category;
                        @endphp
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-sm {{ $cat === null ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                        <a href="{{ route('projects.show', [$project, 'category' => 'backend']) }}" class="btn btn-sm {{ $cat === 'backend' ? 'btn-primary' : 'btn-outline-primary' }}">Backend</a>
                        <a href="{{ route('projects.show', [$project, 'category' => 'frontend']) }}" class="btn btn-sm {{ $cat === 'frontend' ? 'btn-primary' : 'btn-outline-primary' }}">Frontend</a>
                        <a href="{{ route('projects.show', [$project, 'category' => 'server']) }}" class="btn btn-sm {{ $cat === 'server' ? 'btn-primary' : 'btn-outline-primary' }}">Server</a>
                    </div>
                    <a href="{{ route('projects.tasks.create', $project) }}" class="btn btn-primary" style="margin-top:0.25rem;">
                        <i class="fas fa-plus me-2"></i>Add tasks
                    </a>
                @endif
            </div>
        </div>

        <style>
            .board { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; }
            .column { background: #f8f9fa; border-radius: 12px; padding: 1rem; min-height: 420px; border: 1px solid #e9ecef; }
            .column h4 { margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.95rem; font-weight: 700; color: #1a1d21; padding-bottom: 0.75rem; border-bottom: 2px solid #e9ecef; }
            .column-todo h4 { border-bottom-color: #6c757d; }
            .column-in_progress h4 { border-bottom-color: #0dcaf0; }
            .column-in_review h4 { border-bottom-color: #ffc107; }
            .column-done h4 { border-bottom-color: #198754; }
            .column h4 .col-count { margin-left: auto; background: #e9ecef; color: #6c757d; padding: 0.2rem 0.5rem; border-radius: 8px; font-size: 0.8rem; }
            .task-card { background: white; border-radius: 10px; padding: 1rem; margin-bottom: 1rem; box-shadow: 0 1px 4px rgba(0,0,0,0.06); border-left: 4px solid #1D809F; transition: all 0.2s; }
            .task-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
            .task-card .title { font-weight: 700; font-size: 0.95rem; color: #1a1d21; margin-bottom: 0.4rem; display: block; text-decoration: none; }
            .task-card .title:hover { color: #1D809F; }
            .task-card .meta { font-size: 0.8rem; color: #6c757d; line-height: 1.4; }
            .task-card .meta-row { display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; margin-top: 0.5rem; }
            .task-card .badge-assigned { background: #e8f4f8; color: #1D809F; padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.8rem; font-weight: 500; }
            .task-card .comment-preview { margin-top: 0.5rem; padding: 0.5rem; background: #f8f9fa; border-radius: 6px; border-left: 3px solid #1D809F; font-size: 0.8rem; color: #495057; }
            .task-card .card-actions { margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid #eee; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; }
            .task-card .status-select { font-size: 0.8rem; padding: 0.35rem 0.6rem; border-radius: 6px; border: 1px solid #dee2e6; min-width: 110px; }
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
                        <div class="task-card" data-task-id="{{ $task->id }}">
                            <a href="{{ route('tasks.show', $task) }}" class="title">{{ $task->title }}</a>
                            <div class="meta">{{ Str::limit($task->description, 70) }}</div>
                            <div class="meta-row">
                                @if($task->assigned_to)
                                    <span class="badge-assigned"><i class="fas fa-user"></i> {{ $task->assignedUser?->getDisplayName() ?? 'Unassigned' }}</span>
                                @endif
                                <span class="meta">{{ $task->deadline?->format('M d, Y') ?? '—' }}</span>
                            </div>
                            @php $latestComment = $task->comments->first(); @endphp
                            @if($latestComment)
                                <div class="comment-preview">
                                    <span style="font-weight:600; color:#1D809F;">{{ $latestComment->user->getDisplayName() }}:</span>
                                    @php $body = $latestComment->body; $halfLen = (int)(strlen($body) / 2); $previewLen = min(60, $halfLen > 0 ? $halfLen : 30); $showMore = strlen($body) > $previewLen; @endphp
                                    {{ $showMore ? Str::limit($body, $previewLen) : $body }}
                                    @if($showMore) <a href="{{ route('tasks.show', $task) }}#comments" style="font-weight:600; color:#1D809F;">See more</a> @endif
                                </div>
                            @endif
                            <div class="card-actions">
                                <a href="{{ route('tasks.show', $task) }}#comments" class="btn btn-sm btn-outline-secondary btn-link"><i class="fas fa-comments me-1"></i>Comments</a>
                                @if(Auth::user()->id === $task->assigned_to || Auth::user()->isAdminOrCustomer())
                                    <select class="status-select form-select form-select-sm" data-task="{{ $task->id }}">
                                        <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>To Do</option>
                                        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="in_review" {{ $task->status == 'in_review' ? 'selected' : '' }}>In Review</option>
                                        <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done</option>
                                    </select>
                                @endif
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
