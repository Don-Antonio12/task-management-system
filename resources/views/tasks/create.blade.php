<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1 style="margin: 0; font-size: 2rem; font-weight: 700;">
                Create New Task
                @if(request('project_id'))
                    @php
                        $project = \App\Models\Project::find(request('project_id'));
                    @endphp
                    @if($project)
                    <span style="font-size: 1rem; font-weight: 500; color: #667eea; display: block; margin-top: 0.5rem;">for "{{ $project->name }}"</span>
                    @endif
                @endif
            </h1>
        </div>
    </x-slot>

    <style>
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            height: 100%;
            width: 100%;
        }

        .form-group {
            margin-bottom: 2.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.6rem;
            display: block;
            font-size: 0.95rem;
        }

        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #1D809F;
            box-shadow: 0 0 0 3px rgba(29, 128, 159, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #adb5bd;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: #1D809F;
            color: white;
        }

        .btn-primary:hover {
            background: #155d74;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(29, 128, 159, 0.3);
        }

        .btn-secondary {
            background: #e9ecef;
            color: #495057;
        }

        .btn-secondary:hover {
            background: #dee2e6;
        }

        .info-panel {
            background: linear-gradient(135deg, rgba(29, 128, 159, 0.05) 0%, rgba(29, 128, 159, 0.02) 100%);
            border-left: 4px solid #1D809F;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-panel h6 {
            color: #1D809F;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 1rem;
        }

        .info-panel ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-panel li {
            color: #495057;
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-panel i {
            color: #1D809F;
        }
    </style>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="form-section">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="title">Task Title <span style="color: #dc3545;">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="What needs to be done?" required autofocus>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Add details about your task...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="deadline">Deadline</label>
                                <input type="datetime-local" class="form-control @error('deadline') is-invalid @enderror" id="deadline" name="deadline" value="{{ old('deadline') }}">
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status <span style="color: #dc3545;">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="todo" {{ old('status', 'todo') == 'todo' ? 'selected' : '' }}>To Do</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="in_review" {{ old('status') == 'in_review' ? 'selected' : '' }}>In Review</option>
                                    <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Done</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="category">Category <span style="color: #dc3545;">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="backend" {{ old('category') == 'backend' ? 'selected' : '' }}>Backend</option>
                            <option value="frontend" {{ old('category') == 'frontend' ? 'selected' : '' }}>Frontend</option>
                            <option value="server" {{ old('category') == 'server' ? 'selected' : '' }}>Server</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(Auth::user()->isAdminOrCustomer())
                    <div class="form-group">
                        <label for="assigned_to">Assign To</label>
                        <select class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                            <option value="">Unassigned</option>
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
                    @endif

                    @if(Auth::user()->isAdminOrCustomer())
                    <div class="form-group">
                        <label for="project_id">Project</label>
                        <select class="form-select @error('project_id') is-invalid @enderror" id="project_id" name="project_id">
                            <option value="">No Project</option>
                            @foreach(\App\Models\Project::where('status', 'active')->get() as $project)
                                <option value="{{ $project->id }}" @selected(old('project_id') == $project->id || request('project_id') == $project->id)>{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Create Task
                        </button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="info-panel">
                <h6><i class="fas fa-lightbulb"></i> Tips</h6>
                <ul>
                    <li><i class="fas fa-check-circle"></i> Use clear, actionable titles</li>
                    <li><i class="fas fa-check-circle"></i> Add deadline to track progress</li>
                    <li><i class="fas fa-check-circle"></i> Assign to team members</li>
                    <li><i class="fas fa-check-circle"></i> Update status as you progress</li>
                </ul>
            </div>
        </div>
    </div>

    @if(Auth::user()->isAdminOrCustomer())
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
            unassigned.textContent = 'Unassigned';
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

        filterAssignedTo(@json(old('assigned_to')));
    })();
    </script>
    @endif
</x-app-layout>
