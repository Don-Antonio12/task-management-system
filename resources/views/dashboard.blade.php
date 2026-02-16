<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 2rem;">
            <div>
                <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Welcome back, {{ Auth::user()->name }}!</h1>
                <p style="color: #adb5bd; margin-top: 0.5rem;">{{ date('l, F j, Y') }}</p>
            </div>
            @if(Auth::user()->role !== 'admin')
            <a href="{{ route('tasks.create') }}" class="btn btn-primary" style="white-space: nowrap; width: 180px; height: 50px; font-size: 1.1rem;">
                <i class="fas fa-plus"></i> New Task
            </a>
            @endif
        </div>
    </x-slot>

    <style>
        .body{
            border: none;
            margin: 0;
            padding: 0;
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }
        
        main.dashboard-content {
            margin-left: 0 !important;
            margin-right: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        main.dashboard-content > .container {
            max-width: 90% !important;
            width: 90% !important;
            padding-left: 3rem !important;
            padding-right: 3rem !important;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 3rem 2.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 700;
            color:rgb(0, 0, 0);
            margin: 0;
        }

        .stat-label {
            color: #adb5bd;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 0;
        }

        .info-grid.stats-4col {
            grid-template-columns: repeat(4, 1fr);
        }

        .info-grid.stats-3col {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .overview-section {
            margin-bottom: 2.5rem;
        }

        .overview-section-title {
            font-size: 1rem;
            font-weight: 600;
            color:rgb(0, 0, 0);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .overview-section-title i {
            color: #1D809F;
        }
        
        @media (max-width: 992px) {
            .info-grid.stats-4col {
                grid-template-columns: repeat(2, 1fr);
            }
            .info-grid.stats-3col {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .info-grid,
            .info-grid.stats-4col,
            .info-grid.stats-3col {
                grid-template-columns: 1fr;
            }
        }

        .enhanced-card {
            background: white;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            font-size: 1.5rem;
        }

        .enhanced-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .enhanced-card h5 {
            color:rgb(0, 0, 0);
            margin-top: 0;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(29, 128, 159, 0.2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
        }

        .enhanced-card p {
            color:rgb(0, 0, 0);
            margin: 0.75rem 0;
            line-height: 1.6;
            font-size: 1.3rem;
        }

        .user-info-box {
            background: linear-gradient(135deg, rgba(29, 128, 159, 0.1) 0%, rgba(29, 128, 159, 0.05) 100%);
            border-left: 4px solidrgb(0, 0, 0);
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .user-info-box strong {
            color:rgb(0, 0, 0);
            font-size: 0.95rem;
        }
        
        .user-info-box p {
            font-size: 1.5rem;
        }

        .calendar-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color:rgb(0, 0, 0);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.25rem;
            font-size: 0.8rem;
        }

        .calendar-day-header {
            text-align: center;
            font-weight: 600;
            color:rgb(0, 0, 0);
        }

        .calendar-day {
            text-align: center;
            padding: 0.4rem 0;
            border-radius: 6px;
            cursor: default;
        }

        .calendar-day.today {
            background:rgb(0, 0, 0);
            color: #fff;
            font-weight: 700;
        }

        .calendar-day.other-month {
            color:rgba(0, 0, 0, 0.5);
        }

        .social-link {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: rgba(102, 126, 234, 0.2);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: white;
                transition: all 0.3s ease;
                margin: 0 0.5rem;
                border: 2px solid transparent;
            }
            
            .social-link:hover {
                background: #667eea;
                transform: translateY(-3px);
                border-color: #667eea;
            }
            
            
    </style>

    <!-- Overview Cards -->
    <div style="margin-top: 2rem; width: 100%;">

        {{-- Project Stats --}}
        <div class="overview-section">
            <div class="overview-section-title">
                <i class="fas fa-chart-pie"></i> Project Overview
            </div>
            <div class="info-grid {{ Auth::user()->isAdminOrCustomer() ? 'stats-4col' : '' }}" style="width: 100%;">
                <div class="stat-card">
                    <p class="stat-number">{{ $totalProjects ?? 0 }}</p>
                    <p class="stat-label"><i class="fas fa-folder me-1"></i>Total Projects</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number">@if(Auth::user()->isAdminOrCustomer()) {{ $projectInProgress ?? 0 }} @else {{ $inProgressTasks }} @endif</p>
                    <p class="stat-label"><i class="fas fa-tasks me-1"></i>In Progress</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number">@if(Auth::user()->isAdminOrCustomer()) {{ $projectCompleted ?? 0 }} @else {{ $completedTasks }} @endif</p>
                    <p class="stat-label"><i class="fas fa-check-double me-1"></i>Completed</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number">@if(Auth::user()->isAdminOrCustomer()) {{ $projectOverdue ?? 0 }} @else {{ $overdueTasks }} @endif</p>
                    <p class="stat-label"><i class="fas fa-clock me-1"></i>Overdue</p>
                </div>
            </div>
        </div>

        @if(Auth::user()->isAdminOrCustomer())
        {{-- Team Stats --}}
        <div class="overview-section">
            <div class="overview-section-title">
                <i class="fas fa-users"></i> Team
            </div>
            <div class="info-grid stats-3col" style="width: 100%;">
                <div class="stat-card" style="border-left: 4px solid #55077c;">
                    <p class="stat-number" style="color:rgb(0, 0, 0);">{{ $backendCount ?? 0 }}</p>
                    <p class="stat-label"><i class="fas fa-server me-1"></i>Backend Developers</p>
                </div>
                <div class="stat-card" style="border-left: 4px solid #764ba2;">
                    <p class="stat-number" style="color:rgb(0, 0, 0);">{{ $frontendCount ?? 0 }}</p>
                    <p class="stat-label"><i class="fas fa-desktop me-1"></i>Frontend Developers</p>
                </div>
                <div class="stat-card" style="border-left: 4px solid #764ba2;">
                    <p class="stat-number" style="color:rgb(0, 0, 0);">{{ $serverCount ?? 0 }}</p>
                    <p class="stat-label"><i class="fas fa-database me-1"></i>Server Admins</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Main Content -->
    <div class="row mt-4">
        <div class="col-lg-8">
            @if(Auth::user()->isAdminOrCustomer())
            <div class="enhanced-card">
                <h5><i class="fas fa-folder"></i> Projects</h5>
                <p>Manage your projects and organize tasks. You have <strong>{{ $totalProjects ?? 0 }}</strong> project{{ ($totalProjects ?? 0) != 1 ? 's' : '' }}.</p>
                @if(!($projects ?? collect())->isEmpty())
                    <div style="margin-top:1rem; display:flex; flex-direction:column; gap:0.75rem;">
                        @foreach($projects->take(5) as $p)
                            <div style="display:flex; justify-content:space-between; align-items:center; gap:0.75rem;">
                                <div style="flex:1; min-width:0;">
                                    <a href="{{ route('projects.show', $p) }}" style="font-weight:700; color:#1a1a2e; text-decoration:none;">{{ $p->name }}</a>
                                    <div style="font-size:0.85rem; color:#6c757d;">{{ $p->percent_done }}% — {{ $p->done_count }} / {{ $p->tasks_count }} tasks done</div>
                                    <div style="margin-top:0.5rem; background:#e9ecef; border-radius:6px; height:8px; overflow:hidden;">
                                        <div style="width:{{ $p->percent_done }}%; height:8px; background:linear-gradient(135deg,rgb(37, 4, 39) 0%, #764ba2 100%);"></div>
                                    </div>
                                </div>
                                <div style="text-align:right; min-width:100px;">
                                    <span class="badge bg-{{ $p->status === 'completed' ? 'success' : 'secondary' }}">{{ ucfirst($p->status ?? 'active') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <a href="{{ route('projects.index') }}" class="btn btn-sm btn-primary" style="margin-top: 1rem; height: 50px; width: 150px; font-size: 1.1rem; background: #764ba2; color: white; border: none;">
                    View Projects <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @endif

            <div class="enhanced-card" style="margin-top: 1.5rem;">
                <h5><i class="fas fa-lightbulb"></i> Getting Started</h5>
                <p>Welcome to your Task Management System! Use this dashboard to:</p>
                <ul style="color: #495057; margin-top: 1rem;">
                    <li>Create and manage your tasks</li>
                    <li>Set deadlines and track progress</li>
                    <li>Collaborate with team members</li>
                    <li>Monitor overdue items</li>
                </ul>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="enhanced-card calendar-card">
                <h5><i class="fas fa-calendar-alt"></i> Calendar</h5>
                <div class="calendar-header">
                    <span id="adminCalendarMonth"></span>
                </div>
                <div class="calendar-grid" id="adminCalendar"></div>
            </div>

            <div style="height: 1.5rem;"></div>

            <div class="enhanced-card">
                <h5><i class="fas fa-user-circle"></i> Profile</h5>
                <div class="user-info-box">
                    <p><strong>Name</strong></p>
                    <p style="color: #212529; margin-bottom: 1rem;">{{ Auth::user()->name }}</p>
                    <p><strong>Email</strong></p>
                    <p style="color: #212529;">{{ Auth::user()->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary w-100">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
            </div>

            <div class="enhanced-card" style="margin-top: 1.5rem;">
                <h5><i class="fas fa-info-circle"></i> Quick Links</h5>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary" style="width: 100%;">
                        <i class="fas fa-list"></i> View Tasks
                    </a>
                    <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-plus"></i> Create Task
                    </a>
                    @if(Auth::user()->isAdminOrCustomer())
                    <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-folder"></i> Projects
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
    <footer class="footer" style="margin-top: 2.5rem;">
            <div class="container px-4 px-lg-5">
                <div class="row">
                    <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                        <h5 class="mb-2">Task Management System</h5>
                        <p class="text-muted">Organize, track, and manage your tasks efficiently.</p>
                    </div>
                    <div class="col-lg-6 text-center text-lg-end">
                        <a href="#" class="social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-github"></i>
                        </a>
                    </div>
                </div>
                <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
                <div class="text-center">
                    <p class="text-muted small mb-0">&copy; 2026 Task Management System. All rights reserved.</p>
                </div>
            </div>
        </footer>

    </div>
    
    <script>
        (function () {
            function renderCalendar(containerId, labelId) {
                const container = document.getElementById(containerId);
                const label = document.getElementById(labelId);
                if (!container || !label) return;

                const today = new Date();
                const year = today.getFullYear();
                const month = today.getMonth();

                const firstOfMonth = new Date(year, month, 1);
                const startWeekday = firstOfMonth.getDay(); // 0 (Sun) - 6 (Sat)
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const daysInPrevMonth = new Date(year, month, 0).getDate();

                label.textContent = today.toLocaleDateString(undefined, { month: 'long', year: 'numeric' });

                container.innerHTML = '';

                const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                dayNames.forEach(d => {
                    const el = document.createElement('div');
                    el.className = 'calendar-day-header';
                    el.textContent = d;
                    container.appendChild(el);
                });

                const totalCells = 42; // 6 weeks * 7 days
                for (let i = 0; i < totalCells; i++) {
                    const cell = document.createElement('div');
                    cell.className = 'calendar-day';

                    const dayIndex = i - startWeekday + 1;
                    let displayDay = dayIndex;

                    if (dayIndex <= 0) {
                        displayDay = daysInPrevMonth + dayIndex;
                        cell.classList.add('other-month');
                    } else if (dayIndex > daysInMonth) {
                        displayDay = dayIndex - daysInMonth;
                        cell.classList.add('other-month');
                    }

                    cell.textContent = displayDay;

                    if (!cell.classList.contains('other-month') &&
                        displayDay === today.getDate()) {
                        cell.classList.add('today');
                    }

                    container.appendChild(cell);
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function () {
                    renderCalendar('adminCalendar', 'adminCalendarMonth');
                });
            } else {
                renderCalendar('adminCalendar', 'adminCalendarMonth');
            }
        })();
    </script>
</x-app-layout>
