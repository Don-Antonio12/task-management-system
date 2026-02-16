<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; padding: 1.0rem 1.5rem; border-radius: 12px; border: 1px solid #000000;">
            <h1 style="margin:0; font-size:1.6rem; font-weight:700;">
                <i class="fas fa-bell me-2"></i>Notifications
            </h1>
            @if($notifications->count() > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-check-double me-1"></i>Mark all as read
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="container-fluid px-4 px-lg-5 py-4">
        @if($notifications->isEmpty())
            <div style="text-align:center; padding:2.5rem 1.5rem; background:#f8f9fa; border-radius:12px;">
                <i class="fas fa-bell-slash" style="font-size:2.5rem; color:#adb5bd; margin-bottom:0.75rem;"></i>
                <p style="margin:0; color:#6c757d;">No notifications yet.</p>
            </div>
        @else
            <div class="list-group" style="box-shadow:0 1px 4px rgba(0,0,0,0.06); border-radius:12px; overflow:hidden;">
                @foreach($notifications as $notification)
                    @php
                        $isUnread = is_null($notification->read_at);
                        $project = $notification->project;
                        $task = $notification->task;
                    @endphp
                    <div class="list-group-item" style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; padding:0.9rem 1.25rem; background:{{ $isUnread ? '#eef5ff' : '#ffffff' }};">
                        <div>
                            <div style="font-size:0.95rem; {{ $isUnread ? 'font-weight:600;' : '' }}">
                                {!! nl2br(e($notification->message)) !!}
                            </div>
                            <div style="margin-top:0.25rem; font-size:0.8rem; color:#6c757d; display:flex; flex-wrap:wrap; gap:0.75rem;">
                                @if($project)
                                    <span><i class="fas fa-folder-open me-1"></i><a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a></span>
                                @endif
                                @if($task)
                                    <span><i class="fas fa-tasks me-1"></i><a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></span>
                                @endif
                                <span><i class="far fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div>
                            @if($isUnread)
                                <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-check me-1"></i>Mark read
                                    </button>
                                </form>
                            @else
                                <span style="font-size:0.75rem; color:#adb5bd;">Read</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

