<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 style="font-size: 2rem; font-weight: 700; margin: 0;">Create Project</h2>
            <a href="{{ route('projects.index') }}" class="btn btn-secondary" style="background: #764ba2; color: white; border: none;">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </x-slot>

    <div class="container px-4 px-lg-5 py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none;">
                    <div class="card-body p-5">
                        <form action="{{ route('projects.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="form-label" style="font-weight: 600;">Project Name *</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    placeholder="e.g., Mobile App Development"
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
                                    rows="4" 
                                    placeholder="Describe your project...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="priority" class="form-label" style="font-weight: 600;">Priority *</label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                    <option value="">— Select Priority —</option>
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
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
                                        value="{{ old('start_date') }}">
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
                                        value="{{ old('deadline') }}">
                                    @error('deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <p class="text-muted small mb-3">Developers will be automatically assigned using round-robin distribution across all project categories.</p>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="alert alert-info mb-0" style="background: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 0.375rem;">
                                        <small style="font-weight: 600;">Backend Developer</small>
                                        <p class="mt-2 mb-0"><strong>{{ $defaultBackend ? App\Models\User::find($defaultBackend)?->name : 'Pending' }}</strong></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-info mb-0" style="background: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 0.375rem;">
                                        <small style="font-weight: 600;">Frontend Developer</small>
                                        <p class="mt-2 mb-0"><strong>{{ $defaultFrontend ? App\Models\User::find($defaultFrontend)?->name : 'Pending' }}</strong></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-info mb-0" style="background: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 0.375rem;">
                                        <small style="font-weight: 600;">Server Admin</small>
                                        <p class="mt-2 mb-0"><strong>{{ $defaultServer ? App\Models\User::find($defaultServer)?->name : 'Pending' }}</strong></p>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; gap: 1rem;">
                                <button type="submit" class="btn btn-primary btn-lg flex-grow-1" style="background: #764ba2; color: white; border: none;">
                                    <i class="fas fa-check me-2"></i>Create Project
                                </button>
                                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-lg" style="background: #764ba2; color: white; border: none;">
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
