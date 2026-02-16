<x-app-layout>
    <x-slot name="header">
        <div style="padding: 1.5rem 1.5rem; border-radius: 12px; color: #000000; border: 1px solid #000000;">
            <h1 style="margin:0; font-size:2rem; font-weight:700;">
                <i class="fas fa-folder me-2"></i>{{ ucfirst($role) }} Projects
            </h1>
            <p style="margin: 0.5rem 0 0 0; opacity: 0.9; font-size: 0.95rem;">Projects assigned to you</p>
        </div>
    </x-slot>

    <style>
        .dev-project-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border: 1px solid #e9ecef;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: box-shadow 0.2s, transform 0.2s;
            min-height: 180px;
        }
        .dev-project-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.1); transform: translateY(-2px); }
        .dev-project-card .card-header { padding: 1.5rem 1.5rem; border-bottom: 1px solid #f0f0f0; }
        .dev-project-card .card-title { font-weight: 700; font-size: 1.1rem; color: #1a1d21; margin: 0 0 0.35rem 0; text-decoration: none; display: block; }
        .dev-project-card .card-title:hover { color: #1D809F; }
        .dev-project-card .card-desc { font-size: 0.9rem; color: #6c757d; margin: 0; line-height: 1.45; }
        .dev-project-card .card-meta { padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
        .dev-project-card .card-progress { flex: 1; background: #e9ecef; border-radius: 8px; height: 10px; overflow: hidden; }
        .dev-project-card .card-progress-bar { height: 100%; background: linear-gradient(90deg,#667eea,#1D809F); border-radius: 8px; }
        .dev-project-card .card-percent { font-size: 0.9rem; font-weight: 600; color: #1D809F; min-width: 3rem; text-align: right; }
        .dev-project-card .card-footer { padding: 1.25rem 1.5rem; border-top: 1px solid #f0f0f0; }
    </style>

    <div class="container-fluid px-4 px-lg-5 pt-2 pb-4">
        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap:1rem;">
            @forelse($projects as $p)
                <div class="dev-project-card">
                    <div class="card-header">
                        <a href="{{ route('projects.show', $p) }}" class="card-title">{{ $p->name }}</a>
                        <p class="card-desc">{{ Str::limit($p->description, 120) ?: 'No description.' }}</p>
                    </div>
                    <div class="card-meta">
                        <div class="card-progress">
                            <div class="card-progress-bar" style="width:{{ $p->percent_done }}%;"></div>
                        </div>
                        <span class="card-percent">{{ $p->percent_done }}%</span>
                    </div>
                    <div class="card-footer" style="display:flex; flex-direction:column; gap:0.5rem;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="font-size:0.85rem; color:#6c757d;"><i class="fas fa-tasks me-1"></i>{{ $p->assigned_total }} tasks</span>
                            <a href="{{ route('projects.show', $p) }}" class="btn btn-sm btn-primary"><i class="fas fa-folder-open me-1"></i>Open</a>
                        </div>

                        @php
                            $field = $role . '_submission_link';
                            $currentLink = $p->{$field} ?? null;
                        @endphp
                        <form action="{{ route('developer.projects.submission', [$role, $p->id]) }}" method="POST" style="margin-top:0.25rem;">
                            @csrf
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="fas fa-link"></i></span>
                                <input
                                    type="url"
                                    name="submission_link"
                                    class="form-control"
                                    placeholder="Submission link for this project"
                                    value="{{ old('submission_link', $currentLink) }}"
                                >
                                <button class="btn btn-outline-secondary" type="submit">Save</button>
                            </div>
                            @if($currentLink)
                                <small style="font-size:0.75rem; color:#198754;">
                                    Current: <a href="{{ $currentLink }}" target="_blank" rel="noopener noreferrer">{{ \Illuminate\Support\Str::limit($currentLink, 40) }}</a>
                                </small>
                            @endif
                        </form>
                    </div>
                </div>
            @empty
                <p style="color:#adb5bd; font-style:italic; padding:2rem;">No projects assigned.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
