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

                            <div style="display: flex; gap: 1rem;">
                                <button type="submit" class="btn btn-primary btn-lg flex-grow-1" style="background: #764ba2; color: white; border: none;">
                                    <i class="fas fa-check me-2"></i>Create Project
                                </button>
                                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-lg" style="background: #764ba2; color: white; border: none;">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
