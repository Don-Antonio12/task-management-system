<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div>
                <h1 style="margin: 0; font-size: 2rem; font-weight: 700;">Tasks</h1>
                <p style="color: #adb5bd; margin-top: 0.5rem;">Manage your tasks across different stages</p>
            </div>
            @can('create', App\Models\Task::class)
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Task
                </a>
            @endcan
        </div>
    </x-slot>

    <style>
        .trello-board {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .trello-column {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            min-height: 500px;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .trello-column-header {
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            border-bottom: 2px solid rgba(29, 128, 159, 0.2);
        }

        .trello-column-count {
            background: #1D809F;
            color: white;
            border-radius: 20px;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            margin-left: auto;
        }

        .tasks-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            overflow-y: auto;
            max-height: 600px;
        }

        .task-card {
            background: white;
            border-radius: 10px;
            padding: 1.2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-left: 4px solid #1D809F;
            text-decoration: none;
            display: block;
        }

        .task-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
            transform: translateY(-4px);
            border-left-color: #1D809F;
        }

        .task-card.todo {
            border-left-color: #6c757d;
        }

        .task-card.in_progress {
            border-left-color: #0dcaf0;
        }

        .task-card.in_review {
            border-left-color: #ffc107;
        }

        .task-card.done {
            border-left-color: #198754;
            opacity: 0.8;
        }

        .task-title {
            font-weight: 600;
            margin: 0 0 0.7rem 0;
            display: block;
            color: #212529;
            font-size: 0.95rem;
        }

        .task-description {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 1rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .task-footer {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
            font-size: 0.8rem;
        }

        .task-assigned {
            background: #e8f4f8;
            color: #1D809F;
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            font-weight: 500;
        }

        .task-deadline {
            color: #dc3545;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .task-deadline.ok {
            color: #198754;
        }

        .empty-state {
            text-align: center;
            color: #adb5bd;
            padding: 3rem 1rem;
            font-style: italic;
        }

        .column-status-todo .trello-column-header { border-bottom-color: #6c757d; }
        .column-status-in_progress .trello-column-header { border-bottom-color: #0dcaf0; }
        .column-status-in_review .trello-column-header { border-bottom-color: #ffc107; }
        .column-status-done .trello-column-header { border-bottom-color: #198754; }
    </style>

    <div class="trello-board">
        @php
            $statuses = [
                'todo' => ['label' => 'To Do', 'icon' => 'circle'],
                'in_progress' => ['label' => 'In Progress', 'icon' => 'spinner'],
                'in_review' => ['label' => 'In Review', 'icon' => 'eye'],
                'done' => ['label' => 'Done', 'icon' => 'check-circle'],
            ];
        @endphp

        @foreach($statuses as $status => $config)
            <div class="trello-column column-status-{{ $status }}">
                <div class="trello-column-header">
                    <i class="fas fa-{{ $config['icon'] }}" style="color: #1D809F;"></i>
                    <span>{{ $config['label'] }}</span>
                    <span class="trello-column-count">{{ count($tasks[$status] ?? []) }}</span>
                </div>

                <div class="tasks-container">
                    @if(isset($tasks[$status]) && count($tasks[$status]) > 0)
                        @foreach($tasks[$status] as $task)
                            <a href="{{ route('tasks.show', $task) }}" class="task-card {{ $status }}">
                                <span class="task-title">{{ $task->title }}</span>

                                @if($task->description)
                                    <div class="task-description">{{ $task->description }}</div>
                                @endif

                                <div class="task-footer">
                                    @if($task->assigned_to)
                                        <span class="task-assigned">
                                            <i class="fas fa-user"></i> {{ $task->assignedUser->getDisplayName() }}
                                        </span>
                                    @endif

                                    @if($task->deadline)
                                        @php
                                            $isOverdue = \Carbon\Carbon::parse($task->deadline)->isPast();
                                            $daysLeft = \Carbon\Carbon::parse($task->deadline)->diffInDays(now());
                                        @endphp
                                        <span class="task-deadline {{ $isOverdue ? '' : 'ok' }}">
                                            <i class="fas fa-calendar-alt"></i>
                                            @if($isOverdue)
                                                Overdue
                                            @else
                                                {{ $daysLeft }}d left
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-inbox" style="font-size: 2rem; opacity: 0.5;"></i>
                            <p style="margin-top: 0.5rem;">No tasks yet</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
