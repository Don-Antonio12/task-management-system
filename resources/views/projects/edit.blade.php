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
