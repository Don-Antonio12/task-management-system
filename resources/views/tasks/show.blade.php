<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 2rem;">
            <div>
                <h1 style="margin: 0; font-size: 2rem; font-weight: 700;">{{ $task->title }}</h1>
                <p style="color: #adb5bd; margin-top: 0.5rem;">Created {{ $task->created_at->format('M d, Y') }}</p>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                @if(Auth::user()->isAdminOrCustomer())
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary" style="padding: 0.7rem 1.2rem; font-size: 0.9rem;">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger" style="padding: 0.7rem 1.2rem; font-size: 0.9rem;" data-confirm-btn data-confirm-title="Delete Task" data-confirm-message="Are you sure you want to delete this task?">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
                @endif
                <a href="{{ $task->project ? route('projects.show', $task->project) : (Auth::user()->isAdminOrCustomer() ? route('tasks.index') : route('developer.tasks', Auth::user()->role)) }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .detail-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .detail-card h5 {
            color: #1D809F;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(29, 128, 159, 0.2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-section {
            margin-bottom: 1rem;
        }

        .detail-section:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: #1D809F;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.4rem;
        }

        .detail-value {
            color: #212529;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .detail-value.muted {
            color: #adb5bd;
            font-style: italic;
        }

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-todo {
            background: #e7e7e7;
            color: #495057;
        }

        .status-in_progress {
            background: #cfe2ff;
            color: #084298;
        }

        .status-in_review {
            background: #fff3cd;
            color: #664d03;
        }

        .status-done {
            background: #d1e7dd;
            color: #0f5132;
        }

        .description-box {
            background: linear-gradient(135deg, rgba(29, 128, 159, 0.05) 0%, rgba(29, 128, 159, 0.02) 100%);
            border-left: 4px solid #1D809F;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .comments-section { margin-top: 2rem; }
        .comment-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 0.75rem;
            border: 1px solid #e9ecef;
        }
        .comment-card.reply { margin-left: 2rem; background: #fff; border-left: 3px solid #1D809F; }
        .comment-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 0.9rem; }
        .comment-author { font-weight: 600; color: #1D809F; }
        .comment-date { color: #6c757d; font-size: 0.85rem; }
        .comment-body { color: #212529; line-height: 1.5; white-space: pre-wrap; }
        .reply-form { margin-top: 0.75rem; margin-bottom: 0.5rem; }
        .reply-form textarea { font-size: 0.9rem; min-height: 70px; resize: vertical; }
    </style>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="detail-card">
                <h5><i class="fas fa-file-alt"></i> Description</h5>
                @if($task->description)
                    <div class="description-box">
                        {{ $task->description }}
                    </div>
                @else
                    <p class="detail-value muted">No description provided</p>
                @endif
            </div>

            <div class="detail-card">
                <h5><i class="fas fa-history"></i> Activity</h5>
                <div class="detail-section">
                    <div class="detail-label">Created</div>
                    <div class="detail-value">{{ $task->created_at->format('F j, Y \a\t g:i A') }}</div>
                </div>
                <div class="detail-section">
                    <div class="detail-label">Last Updated</div>
                    <div class="detail-value">{{ $task->updated_at->format('F j, Y \a\t g:i A') }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="detail-card">
                <h5><i class="fas fa-tasks"></i> Status</h5>
                <div style="margin-bottom: 1.5rem;">
                    @php
                        $statusClasses = [
                            'todo' => 'status-todo',
                            'in_progress' => 'status-in_progress',
                            'in_review' => 'status-in_review',
                            'done' => 'status-done',
                        ];
                        $statusLabels = [
                            'todo' => 'To Do',
                            'in_progress' => 'In Progress',
                            'in_review' => 'In Review',
                            'done' => 'Done',
                        ];
                    @endphp
                    <span class="status-badge {{ $statusClasses[$task->status] ?? 'status-todo' }}">
                        <i class="fas fa-check-circle"></i> {{ $statusLabels[$task->status] ?? $task->status }}
                    </span>
                </div>

                @if(Auth::user()->id === $task->assigned_to || Auth::user()->isAdminOrCustomer())
                    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                        <button class="btn btn-sm btn-outline-secondary status-btn" data-task="{{ $task->id }}" data-status="todo">To Do</button>
                        <button class="btn btn-sm btn-primary status-btn" data-task="{{ $task->id }}" data-status="in_progress">In Progress</button>
                        <button class="btn btn-sm btn-outline-warning status-btn" data-task="{{ $task->id }}" data-status="in_review">In Review</button>
                        <button class="btn btn-sm btn-success status-btn" data-task="{{ $task->id }}" data-status="done">Done</button>
                    </div>
                @else
                    <small class="meta">You cannot change this task.</small>
                @endif
            </div>

            <!-- Details Card -->
            <div class="detail-card">
                <h5><i class="fas fa-info-circle"></i> Details</h5>

                <div class="detail-section">
                    <div class="detail-label"><i class="fas fa-user-circle"></i> Created by</div>
                    <div class="detail-value">{{ $task->user->getDisplayName() }}</div>
                </div>

                <div class="detail-section">
                    <div class="detail-label"><i class="fas fa-user-check"></i> Assigned to</div>
                    @if($task->assigned_to)
                        <div class="detail-value">{{ $task->assignedUser->getDisplayName() }}</div>
                    @else
                        <div class="detail-value muted">Not assigned</div>
                    @endif
                </div>

                <div class="detail-section">
                    <div class="detail-label"><i class="fas fa-calendar-alt"></i> Deadline</div>
                    @if($task->deadline)
                        @php
                            $isOverdue = \Carbon\Carbon::parse($task->deadline)->isPast();
                            $daysLeft = \Carbon\Carbon::parse($task->deadline)->diffInDays(now());
                        @endphp
                        <div class="detail-value" style="color: {{ $isOverdue && $task->status != 'done' ? '#dc3545' : '#198754' }};">
                            {{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y H:i') }}
                            @if($isOverdue && $task->status != 'done')
                                <br><small>(Overdue by {{ $daysLeft }} days)</small>
                            @elseif($task->status != 'done')
                                <br><small>({{ $daysLeft }} days remaining)</small>
                            @endif
                        </div>
                    @else
                        <div class="detail-value muted">No deadline set</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Comments -->
    <div id="comments" class="comments-section mt-4">
        <div class="detail-card">
            <h5><i class="fas fa-comments"></i> Comments</h5>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->has('body'))
                <div class="alert alert-danger py-2">{{ $errors->first('body') }}</div>
            @endif

            @if(Auth::user()->isAdminOrCustomer())
            <form action="{{ route('tasks.comments.store', $task) }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-2">
                    <label for="comment-body" class="form-label fw-semibold small">Add a comment</label>
                    <textarea name="body" id="comment-body" class="form-control @error('body') is-invalid @enderror" rows="3" placeholder="Write a comment..." required>{{ old('body') }}</textarea>
                    @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-paper-plane me-1"></i> Post comment</button>
            </form>
            @endif

            @forelse($task->comments as $comment)
                <div class="comment-card">
                    <div class="comment-header">
                        <span class="comment-author">{{ $comment->user->getDisplayName() }}</span>
                        <span class="comment-date">{{ $comment->created_at->format('M d, Y g:i A') }}</span>
                    </div>
                    <div class="comment-body">{{ $comment->body }}</div>

                    @foreach($comment->replies as $reply)
                        <div class="comment-card reply">
                            <div class="comment-header">
                                <span class="comment-author">{{ $reply->user->getDisplayName() }}</span>
                                <span class="comment-date">{{ $reply->created_at->format('M d, Y g:i A') }}</span>
                            </div>
                            <div class="comment-body">{{ $reply->body }}</div>
                        </div>
                    @endforeach

                    <div class="reply-form">
                        <form action="{{ route('tasks.comments.store', $task) }}" method="POST">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <textarea name="body" class="form-control mb-2 @error('parent_id') is-invalid @enderror" rows="2" placeholder="Reply..." required></textarea>
                            @error('parent_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            <button type="submit" class="btn btn-outline-primary btn-sm"><i class="fas fa-reply me-1"></i> Reply</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0">No comments yet. @if(Auth::user()->isAdminOrCustomer()) Add one above. @else Admin may add comments; you can reply to them. @endif</p>
            @endforelse
        </div>
    </div>

    <script>
        (function(){
            if (window.TMS_STATUS_HANDLER) return;
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

            const statusMap = {
                todo: { label: 'To Do', cls: 'status-todo' },
                in_progress: { label: 'In Progress', cls: 'status-in_progress' },
                in_review: { label: 'In Review', cls: 'status-in_review' },
                done: { label: 'Done', cls: 'status-done' },
            };

            function init() {
                document.querySelectorAll('.status-btn').forEach(btn => {
                    btn.addEventListener('click', async function(e){
                        e.preventDefault();
                        const taskId = this.getAttribute('data-task');
                        const status = this.getAttribute('data-status');
                        if (!taskId || !status) return;

                        const buttons = Array.from(document.querySelectorAll('.status-btn'));
                        buttons.forEach(b => b.disabled = true);

                        try {
                            const res = await fetch('/tasks/' + taskId + '/status', {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ status })
                            });

                            if (!res.ok) throw new Error('HTTP ' + res.status);
                            const data = await res.json();
                            if (!data.success) throw new Error(data.message || 'Failed');

                            // update badge
                            const badge = document.querySelector('.status-badge');
                            if (badge && statusMap[status]) {
                                badge.className = 'status-badge ' + statusMap[status].cls;
                                badge.innerHTML = '<i class="fas fa-check-circle"></i> ' + statusMap[status].label;
                            }
                        } catch (err) {
                            console.error(err);
                            (window.TMS && window.TMS.showErrorModal) ? TMS.showErrorModal('Failed to update status: ' + (err.message || 'error')) : alert('Failed to update status: ' + (err.message || 'error'));
                        } finally {
                            buttons.forEach(b => b.disabled = false);
                        }
                    });
                });
            }

            if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
            else init();
        })();
    </script>
</x-app-layout>
