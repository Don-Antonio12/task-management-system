<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%;">
            <div>
                <h1 style="margin:0; font-size:2rem; font-weight:700; color: #1a1d21;">
                    <i class="fas fa-tasks me-2"></i>{{ ucfirst($role) }} Tasks
                </h1>
                <p style="margin: 0.5rem 0 0 0; color: #6c757d; font-size: 0.95rem;">Your assigned tasks</p>
            </div>
            <a href="{{ route('developer.index', $role) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </x-slot>

    <style>
        .board { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; }
        .column { background: #f8f9fa; border-radius: 12px; padding: 1rem; min-height: 420px; border: 1px solid #e9ecef; }
        .column h4 { margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.95rem; font-weight: 700; color: #1a1d21; padding-bottom: 0.75rem; border-bottom: 2px solid #e9ecef; }
        .column-todo h4 { border-bottom-color: #6c757d; }
        .column-in_progress h4 { border-bottom-color: #0dcaf0; }
        .column-in_review h4 { border-bottom-color: #ffc107; }
        .column-done h4 { border-bottom-color: #198754; }
        .column h4 .count { margin-left: auto; background: #e9ecef; color: #6c757d; padding: 0.2rem 0.5rem; border-radius: 8px; font-size: 0.8rem; }
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
        .task-card .submission-bin {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px dashed #dee2e6;
        }
        .task-card .submission-bin label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #1D809F;
            margin-bottom: 0.25rem;
        }
        .task-card .submission-bin textarea {
            font-size: 0.8rem;
            resize: vertical;
            min-height: 55px;
        }
        .task-card .submission-bin button {
            margin-top: 0.35rem;
            font-size: 0.8rem;
            padding: 0.25rem 0.6rem;
        }
        @media (max-width: 1200px) { .board { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) { .board { grid-template-columns: 1fr; } }
    </style>

    <div style="padding:1rem 1.5rem;">
        <div class="board">
            @php
                $statuses = [
                    'todo' => ['label' => 'To Do', 'class' => 'todo'],
                    'in_progress' => ['label' => 'In Progress', 'class' => 'in_progress'],
                    'in_review' => ['label' => 'In Review', 'class' => 'in_review'],
                    'done' => ['label' => 'Done', 'class' => 'done'],
                ];

                $grouped = collect($tasks)->groupBy('status');
            @endphp

            @foreach($statuses as $statusKey => $cfg)
                <div class="column column-{{ $cfg['class'] }}">
                    <h4><i class="fas fa-{{ $statusKey == 'in_progress' ? 'spinner' : ($statusKey == 'in_review' ? 'eye' : ($statusKey == 'done' ? 'check-circle' : 'inbox')) }}"></i> {{ $cfg['label'] }} <span class="count">{{ count($grouped[$statusKey] ?? []) }}</span></h4>

                    @forelse($grouped[$statusKey] ?? [] as $task)
                        <div class="task-card card" data-task-id="{{ $task->id }}">
                            <a href="{{ route('tasks.show', $task) }}" class="title">{{ $task->title }}</a>
                            <div class="meta">{{ Str::limit($task->description, 70) }}</div>
                            <div class="meta-row">
                                <span class="badge-assigned"><i class="fas fa-user"></i> {{ $task->assignedUser?->getDisplayName() ?? 'Unassigned' }}</span>
                                <span class="meta">{{ $task->deadline?->format('M d, Y') ?? '—' }}</span>
                                @if($task->project)
                                    <a href="{{ route('projects.show', $task->project) }}" class="meta" style="text-decoration:none;">{{ $task->project->name }}</a>
                                @endif
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
                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-secondary btn-link"><i class="fas fa-comments me-1"></i>Comments</a>
                                @if(Auth::user()->id === $task->assigned_to || Auth::user()->isAdminOrCustomer())
                                    <select class="status-select form-select form-select-sm" data-task="{{ $task->id }}">
                                        <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>To Do</option>
                                        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="in_review" {{ $task->status == 'in_review' ? 'selected' : '' }}>In Review</option>
                                        <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done</option>
                                    </select>
                                @endif
                            </div>

                            @if(Auth::user()->id === $task->assigned_to)
                                <div class="submission-bin">
                                    <form action="{{ route('tasks.comments.store', $task) }}" method="POST">
                                        @csrf
                                        <label for="submission-link-{{ $task->id }}"><i class="fas fa-inbox me-1"></i>Submission link</label>
                                        <input id="submission-link-{{ $task->id }}" type="url" name="link" class="form-control mb-1" placeholder="https://example.com/your-work" />
                                        <textarea id="submission-{{ $task->id }}" name="body" class="form-control" placeholder="Notes (optional): describe what you completed"></textarea>
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fas fa-paper-plane me-1"></i>Submit
                                        </button>
                                    </form>
                                </div>
                            @endif
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
            if (window.TMS_STATUS_HANDLER) return;
            window.TMS_STATUS_HANDLER = true;
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            document.querySelectorAll('.status-select').forEach(function(sel) {
                sel.addEventListener('change', async function() {
                    const taskId = this.getAttribute('data-task');
                    const status = this.value;
                    const card = this.closest('.task-card');
                    if (!taskId || !status) return;
                    const prevVal = this.value;
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
                            const span = col.querySelector('h4 .count');
                            if (span) span.textContent = col.querySelectorAll('.task-card').length;
                        });
                    } catch (err) {
                        console.error(err);
                        this.value = card.closest('.column')?.className.match(/column-(\w+)/)?.[1] || prevVal;
                        (window.TMS && window.TMS.showErrorModal) ? TMS.showErrorModal('Failed to update: ' + (err.message || 'error')) : alert('Failed to update: ' + (err.message || 'error'));
                    } finally {
                        this.disabled = false;
                    }
                });
            });
        })();
    </script>
</x-app-layout>
