<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="margin: 0; font-size: 2rem; font-weight: 700;">Add Tasks to Project</h1>
                <span style="font-size: 1rem; font-weight: 500; color: #764ba2; display: block; margin-top: 0.5rem;">{{ $project->name }}</span>
            </div>
            <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary" style="white-space: nowrap; background: #764ba2; color: white; border: none;">
                <i class="fas fa-arrow-left"></i> Back to Project
            </a>
        </div>
    </x-slot>

    <style>
        .form-section { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05); }
        .task-row {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e9ecef;
        }
        .task-row-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .task-row-head h3 { margin: 0; font-size: 1rem; font-weight: 700; color: #764ba2; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { font-weight: 600; color: #212529; margin-bottom: 0.4rem; display: block; font-size: 0.9rem; }
        .form-control, .form-select { border: 1px solid #dee2e6; border-radius: 8px; padding: 0.6rem 0.75rem; font-size: 0.95rem; width: 100%; }
        .form-control:focus, .form-select:focus { border-color: #1D809F; box-shadow: 0 0 0 2px rgba(29, 128, 159, 0.15); outline: none; }
        textarea.form-control { resize: vertical; min-height: 80px; }
        .btn-remove-row { padding: 0.35rem 0.75rem; font-size: 0.85rem; border-radius: 6px; }
        .btn-add-task { margin-bottom: 1.5rem; }
        .button-group { display: flex; gap: 1rem; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #dee2e6; }
        .btn { padding: 0.75rem 1.5rem; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; font-size: 0.95rem; }
        .btn-primary { background: #764ba2; color: white; }
        .btn-primary:hover { background: #155d74; }
        .btn-secondary { background: #764ba2; color: white; border: none; }
        .btn-outline-danger { background: transparent; color: #dc3545; border: 1px solid #dc3545; }
        .btn-outline-danger:hover { background: #dc3545; color: white; }
    </style>

    <div class="row mt-4">
        <div class="col-lg-10">
            <!-- Success modal (shows after creating a project) -->
            <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-check-circle text-success me-2"></i> Success</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="proceedAddTaskBtn" class="btn btn-primary">Proceed to Add Tasks</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        try {
                            var modalEl = document.getElementById('successModal');
                            if (modalEl) {
                                var bsModal = new bootstrap.Modal(modalEl);
                                bsModal.show();

                                // Proceed button: hide modal and scroll to task form
                                var proceedBtn = document.getElementById('proceedAddTaskBtn');
                                if (proceedBtn) {
                                    proceedBtn.addEventListener('click', function() {
                                        try { bsModal.hide(); } catch (err) { /* ignore */ }
                                        var target = document.getElementById('task-rows');
                                        if (target) {
                                            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                            var firstInput = target.querySelector('input, textarea, select');
                                            if (firstInput) firstInput.focus();
                                        }
                                    });
                                }
                            }
                        } catch (e) {
                            console.warn('Could not show success modal:', e);
                        }
                    });
                </script>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('projects.tasks.store', $project) }}" method="POST" id="tasks-form">
                @csrf
                <p class="text-muted small mb-3"><i class="fas fa-info-circle"></i> Assignee is set automatically from the developers assigned to this project (by category).</p>
                <div id="task-rows">
                    @php
                    $oldTasks = old('tasks', [['title' => '', 'description' => '', 'category' => 'backend', 'status' => 'todo']]);
                    @endphp
                    @foreach($oldTasks as $idx => $t)
                    <div class="task-row" data-index="{{ $idx }}">
                        <div class="task-row-head">
                            <h3>Task #<span class="row-num">{{ $idx + 1 }}</span></h3>
                            @if($idx > 0)
                            <button type="button" class="btn btn-outline-danger btn-remove-row" aria-label="Remove task"> <i class="fas fa-trash"></i> Remove</button>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="tasks[{{ $idx }}][title]" value="{{ $t['title'] ?? '' }}" placeholder="Task title" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="tasks[{{ $idx }}][description]" placeholder="Optional details">{{ $t['description'] ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Category <span class="text-danger">*</span></label>
                                    <select class="form-select" name="tasks[{{ $idx }}][category]" required>
                                        <option value="backend" {{ ($t['category'] ?? '') == 'backend' ? 'selected' : '' }}>Backend</option>
                                        <option value="frontend" {{ ($t['category'] ?? '') == 'frontend' ? 'selected' : '' }}>Frontend</option>
                                        <option value="server" {{ ($t['category'] ?? '') == 'server' ? 'selected' : '' }}>Server</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-select" name="tasks[{{ $idx }}][status]">
                                        <option value="todo" {{ ($t['status'] ?? 'todo') == 'todo' ? 'selected' : '' }}>To Do</option>
                                        <option value="in_progress" {{ ($t['status'] ?? '') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="in_review" {{ ($t['status'] ?? '') == 'in_review' ? 'selected' : '' }}>In Review</option>
                                        <option value="done" {{ ($t['status'] ?? '') == 'done' ? 'selected' : '' }}>Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-secondary btn-add-task" id="add-task-btn">
                    <i class="fas fa-plus me-2"></i>Add another task
                </button>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i> Create tasks
                    </button>
                    <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary" style="background: #764ba2; color: white; border: none;" onclick="return confirm('Are you sure you want to cancel? Any changes you made will not be saved.');">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <template id="task-row-tpl">
        <div class="task-row" data-index="__INDEX__">
            <div class="task-row-head">
                <h3>Task #<span class="row-num">__INDEX__</span></h3>
                <button type="button" class="btn btn-outline-danger btn-remove-row"><i class="fas fa-trash"></i> Remove</button>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="tasks[__INDEX__][title]" placeholder="Task title" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="tasks[__INDEX__][description]" placeholder="Optional details"></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Category <span class="text-danger">*</span></label>
                        <select class="form-select" name="tasks[__INDEX__][category]" required>
                            <option value="backend">Backend</option>
                            <option value="frontend">Frontend</option>
                            <option value="server">Server</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-select" name="tasks[__INDEX__][status]">
                            <option value="todo" selected>To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="in_review">In Review</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
    (function() {
        var container = document.getElementById('task-rows');
        var tpl = document.getElementById('task-row-tpl');
        var addBtn = document.getElementById('add-task-btn');
        if (!container || !tpl || !addBtn) return;

        function getNextIndex() {
            var rows = container.querySelectorAll('.task-row');
            var max = -1;
            rows.forEach(function(r) {
                var i = parseInt(r.getAttribute('data-index'), 10);
                if (!isNaN(i) && i > max) max = i;
            });
            return max + 1;
        }

        function updateRowNumbers() {
            container.querySelectorAll('.task-row').forEach(function(row, i) {
                row.setAttribute('data-index', i);
                var numSpan = row.querySelector('.row-num');
                if (numSpan) numSpan.textContent = i + 1;
                row.querySelectorAll('[name^="tasks["]').forEach(function(input) {
                    input.name = input.name.replace(/tasks\[\d+\]/, 'tasks[' + i + ']');
                });
            });
        }

        addBtn.addEventListener('click', function() {
            var index = getNextIndex();
            var html = tpl.innerHTML.replace(/__INDEX__/g, index);
            var div = document.createElement('div');
            div.innerHTML = html.trim();
            var row = div.firstChild;
            container.appendChild(row);
            row.querySelector('.row-num').textContent = index + 1;
        });

        container.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-remove-row');
            if (!btn) return;
            var row = btn.closest('.task-row');
            if (container.querySelectorAll('.task-row').length <= 1) return;
            row.remove();
            updateRowNumbers();
        });
    })();
    </script>
</x-app-layout>
