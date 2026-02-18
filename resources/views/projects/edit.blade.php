<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 style="font-size: 2rem; font-weight: 700; margin: 0;">Edit Project</h2>
            <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary" style="background: #764ba2; color: white; border: none;">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
        </div>
    </x-slot>

    <div class="container px-4 px-lg-5 py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none;">
                    <div class="card-body p-5">
                        <form action="{{ route('projects.update', $project) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="name" class="form-label" style="font-weight: 600;">Project Name *</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', $project->name) }}" 
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label" style="font-weight: 600;">Description</label>
                                <textarea 
                                    class="form-control @error('description') is-invalid @enderror" 
                                    id="description" 
                                    name="description" 
                                    rows="4">{{ old('description', $project->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label" style="font-weight: 600;">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" @selected(old('status', $project->status) === 'active')>Active</option>
                                    <option value="completed" @selected(old('status', $project->status) === 'completed')>Completed</option>
                                    <option value="archived" @selected(old('status', $project->status) === 'archived')>Archived</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="priority" class="form-label" style="font-weight: 600;">Priority *</label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                    <option value="low" @selected(old('priority', $project->priority) === 'low')>Low</option>
                                    <option value="medium" @selected(old('priority', $project->priority) === 'medium')>Medium</option>
                                    <option value="high" @selected(old('priority', $project->priority) === 'high')>High</option>
                                    <option value="urgent" @selected(old('priority', $project->priority) === 'urgent')>Urgent</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label" style="font-weight: 600;">Start Date</label>
                                    <input 
                                        type="date" 
                                        class="form-control @error('start_date') is-invalid @enderror" 
                                        id="start_date" 
                                        name="start_date" 
                                        value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="deadline" class="form-label" style="font-weight: 600;">Deadline</label>
                                    <input 
                                        type="date" 
                                        class="form-control @error('deadline') is-invalid @enderror" 
                                        id="deadline" 
                                        name="deadline" 
                                        value="{{ old('deadline', $project->deadline?->format('Y-m-d')) }}">
                                    @error('deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label">Backend developer</label>
                                    @if($project->backend_assigned_to)
                                        <div style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 0.75rem 1rem;">
                                            <div style="color: #495057; font-weight: 500;">
                                                <i class="fas fa-lock" style="color: #764ba2; margin-right: 0.5rem;"></i>
                                                {{ $project->backendDeveloper->name ?? 'Unknown' }}
                                            </div>
                                            <small style="color: #6c757d; display: block; margin-top: 0.5rem;">
                                                Assigned (cannot change)
                                            </small>
                                        </div>
                                    @else
                                        <select class="form-select" id="backend_assigned_to" name="backend_assigned_to">
                                            <option value="">— None —</option>
                                            @foreach($backendUsers as $u)
                                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Frontend developer</label>
                                    @if($project->frontend_assigned_to)
                                        <div style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 0.75rem 1rem;">
                                            <div style="color: #495057; font-weight: 500;">
                                                <i class="fas fa-lock" style="color: #764ba2; margin-right: 0.5rem;"></i>
                                                {{ $project->frontendDeveloper->name ?? 'Unknown' }}
                                            </div>
                                            <small style="color: #6c757d; display: block; margin-top: 0.5rem;">
                                                Assigned (cannot change)
                                            </small>
                                        </div>
                                    @else
                                        <select class="form-select" id="frontend_assigned_to" name="frontend_assigned_to">
                                            <option value="">— None —</option>
                                            @foreach($frontendUsers as $u)
                                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Server admin</label>
                                    @if($project->server_assigned_to)
                                        <div style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 0.75rem 1rem;">
                                            <div style="color: #495057; font-weight: 500;">
                                                <i class="fas fa-lock" style="color: #764ba2; margin-right: 0.5rem;"></i>
                                                {{ $project->serverDeveloper->name ?? 'Unknown' }}
                                            </div>
                                            <small style="color: #6c757d; display: block; margin-top: 0.5rem;">
                                                Assigned (cannot change)
                                            </small>
                                        </div>
                                    @else
                                        <select class="form-select" id="server_assigned_to" name="server_assigned_to">
                                            <option value="">— None —</option>
                                            @foreach($serverUsers as $u)
                                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>

                            <div style="display: flex; gap: 1rem;">
                                <button type="submit" class="btn btn-primary btn-lg flex-grow-1" style="background: #764ba2; color: white; border: none;">
                                    <i class="fas fa-check me-2"></i>Update Project
                                </button>
                                <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-secondary btn-lg" style="background: #764ba2; color: white; border: none;" onclick="return confirm('Are you sure you want to cancel? Any changes you made will not be saved.');">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
