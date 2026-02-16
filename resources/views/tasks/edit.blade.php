<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1 style="margin: 0; font-size: 2rem; font-weight: 700;">Edit Task</h1>
                <p style="color: #adb5bd; margin-top: 0.5rem;">{{ $task->title }}</p>
            </div>
            <a href="{{ url()->previous() ?? route('dashboard') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
        </div>
    </x-slot>

    <style>
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .form-section h5 {
            color: #1D809F;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(29, 128, 159, 0.2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            font-weight: 600;
            color: #1D809F;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control,
        .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #1D809F;
            box-shadow: 0 0 0 3px rgba(29, 128, 159, 0.1);
            outline: none;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: block;
        }

        .button-group {
            display: flex;
            gap: 0.75rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #1D809F;
            color: white;
        }

        .btn-primary:hover {
            background: #155d74;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(29, 128, 159, 0.2);
        }

        .btn-secondary {
            background: #e9ecef;
            color: #212529;
        }

        .btn-secondary:hover {
            background: #dee2e6;
            transform: translateY(-2px);
        }

        .info-panel {
            background: linear-gradient(135deg, rgba(29, 128, 159, 0.05) 0%, rgba(29, 128, 159, 0.02) 100%);
            border-left: 4px solid #1D809F;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 0;
        }

        .info-panel h6 {
            color: #1D809F;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-item {
            margin-bottom: 1rem;
            font-size: 0.95rem;
            color: #212529;
        }

        .info-item strong {
            color: #1D809F;
            display: block;
            margin-bottom: 0.25rem;
        }
    </style>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="form-section">
                <h5><i class="fas fa-tasks"></i> Edit Task Details</h5>
                <form action="{{ route('tasks.update', $task) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label for="title" class="form-label">Task Title <span style="color: #dc3545;">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $task->title) }}" placeholder="Enter task title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Enter task description">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="deadline" class="form-label">Deadline</label>
                        <input type="datetime-local" class="form-control @error('deadline') is-invalid @enderror" id="deadline" name="deadline" value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '') }}">
                        @error('deadline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category" class="form-label">Category <span style="color: #dc3545;">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="backend" {{ old('category', $task->category) == 'backend' ? 'selected' : '' }}>Backend</option>
                            <option value="frontend" {{ old('category', $task->category) == 'frontend' ? 'selected' : '' }}>Frontend</option>
                            <option value="server" {{ old('category', $task->category) == 'server' ? 'selected' : '' }}>Server</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="assigned_to" class="form-label">Assign to</label>
                        <select class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                            <option value="">-- Not assigned --</option>
                            @foreach($backendUsers as $u)
                            <option value="{{ $u->id }}" data-role="backend">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                            @foreach($frontendUsers as $u)
                            <option value="{{ $u->id }}" data-role="frontend">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                            @foreach($serverUsers as $u)
                            <option value="{{ $u->id }}" data-role="server">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Status <span style="color: #dc3545;">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="todo" {{ old('status', $task->status) == 'todo' ? 'selected' : '' }}>To Do</option>
                            <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="in_review" {{ old('status', $task->status) == 'in_review' ? 'selected' : '' }}>In Review</option>
                            <option value="done" {{ old('status', $task->status) == 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Task
                        </button>
                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-section info-panel">
                <h6><i class="fas fa-info-circle"></i> Task Information</h6>
                
                <div class="info-item">
                    <strong>Created On</strong>
                    {{ $task->created_at->format('M d, Y H:i') }}
                </div>

                <div class="info-item">
                    <strong>Last Updated</strong>
                    {{ $task->updated_at->format('M d, Y H:i') }}
                </div>

                <div class="info-item">
                    <strong>Created by</strong>
                    {{ $task->user->getDisplayName() }}
                </div>

                <div class="info-item">
                    <strong>Current Status</strong>
                    <span style="display: inline-block; padding: 0.35rem 0.75rem; border-radius: 6px; background: #1D809F; color: white; font-size: 0.85rem; font-weight: 600;">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function() {
        var categorySelect = document.getElementById('category');
        var assignedSelect = document.getElementById('assigned_to');
        if (!categorySelect || !assignedSelect) return;

        var developersByCategory = { backend: [], frontend: [], server: [] };
        assignedSelect.querySelectorAll('option[data-role]').forEach(function(opt) {
            var role = opt.getAttribute('data-role');
            if (developersByCategory[role]) {
                developersByCategory[role].push({ value: opt.value, text: opt.textContent });
            }
        });

        function filterAssignedTo(forceSelectedId) {
            var cat = categorySelect.value;
            var list = developersByCategory[cat] || [];
            var currentVal = forceSelectedId !== undefined ? String(forceSelectedId) : assignedSelect.value;
            assignedSelect.innerHTML = '';
            var unassigned = document.createElement('option');
            unassigned.value = '';
            unassigned.textContent = '-- Not assigned --';
            assignedSelect.appendChild(unassigned);
            list.forEach(function(o) {
                var opt = document.createElement('option');
                opt.value = o.value;
                opt.textContent = o.text;
                opt.setAttribute('data-role', cat);
                if (o.value === currentVal) opt.selected = true;
                assignedSelect.appendChild(opt);
            });
        }

        categorySelect.addEventListener('change', function() {
            filterAssignedTo();
        });

        filterAssignedTo(@json(old('assigned_to', $task->assigned_to)));
    })();
    </script>
</x-app-layout>
