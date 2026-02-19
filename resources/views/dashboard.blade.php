<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 2rem;">
            <div>
                <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Welcome back, {{ Auth::user()->name }}!</h1>
                <p style="color: #adb5bd; margin-top: 0.5rem;">{{ date('l, F j, Y') }}</p>
            </div>
            @if(!in_array(Auth::user()->role, ['admin', 'customer']))
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

        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .chart-wrapper {
            position: relative;
            height: 320px;
            width: 100%;
        }

        .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
            margin-bottom: 2.5rem;
        }

        @media (max-width: 1200px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }

</style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Overview Cards -->
    <div style="margin-top: 2rem; width: 100%;">

        {{-- Project Stats --}}
        <div class="overview-section">
            <div class="overview-section-title">
                <i class="fas fa-chart-pie"></i> Project Overview
            </div>
            <div class="info-grid {{ Auth::user()->isAdminOrCustomer() ? 'stats-4col' : '' }}" style="width: 100%;">
                <div class="stat-card">
                    <p id="totalProjectsCount" class="stat-number">{{ $totalProjects ?? 0 }}</p>
                    <p class="stat-label"><i class="fas fa-folder me-1"></i>Total Projects</p>
                </div>
                <div class="stat-card">
                    <p id="inProgressCount" class="stat-number">@if(Auth::user()->isAdminOrCustomer()) {{ $projectInProgress ?? 0 }} @else {{ $inProgressTasks }} @endif</p>
                    <p class="stat-label"><i class="fas fa-tasks me-1"></i>In Progress</p>
                </div>
                <div class="stat-card">
                    <p id="completedCount" class="stat-number">@if(Auth::user()->isAdminOrCustomer()) {{ $projectCompleted ?? 0 }} @else {{ $completedTasks }} @endif</p>
                    <p class="stat-label"><i class="fas fa-check-double me-1"></i>Completed</p>
                </div>
                <div class="stat-card">
                    <p id="overdueCount" class="stat-number">@if(Auth::user()->isAdminOrCustomer()) {{ $projectOverdue ?? 0 }} @else {{ $overdueTasks }} @endif</p>
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

        @if(Auth::user()->isAdminOrCustomer())
        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-pie" style="color: #1D809F;"></i>
                    Project Status Distribution
                </div>
                <div class="chart-wrapper">
                    <canvas id="projectStatusChart"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-tasks" style="color: #1D809F;"></i>
                    Task Status by Project
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label for="projectFilterSelect" style="font-weight: 600; color: #495057; font-size: 0.9rem; display: block; margin-bottom: 0.5rem;">Select Project:</label>
                    <select id="projectFilterSelect" class="form-select" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.95rem;">
                        <option value="">-- Choose a project --</option>
                        @foreach($projects as $p)
                        <option value="{{ $p->id }}" @if($loop->first)selected @endif>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="chart-wrapper">
                    <canvas id="taskStatusChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        {{-- Projects Section --}}
    <div class="row mt-4">
        <div class="col-lg-8">
            @if(Auth::user()->isAdminOrCustomer())
            <div class="enhanced-card">
                <h5><i class="fas fa-folder"></i> Projects</h5>
                <p>Manage your projects and organize tasks. You have <strong>{{ $totalProjects ?? 0 }}</strong> project{{ ($totalProjects ?? 0) != 1 ? 's' : '' }}.</p>
                @if(!($projects ?? collect())->isEmpty())
                    <div style="margin-top:1rem; display:flex; flex-direction:column; gap:1rem;">
                        @foreach($projects->take(5) as $p)
                            <div style="display:flex; flex-direction:column; gap:0.75rem; padding:1rem; background:#f8f9fa; border-radius:8px; border:1px solid #e9ecef;">
                                <div style="display:flex; justify-content:space-between; align-items:center; gap:0.75rem;">
                                    <div style="flex:1; min-width:0;">
                                        <a href="{{ route('projects.show', $p) }}" style="font-weight:700; color:#1a1a2e; text-decoration:none;">{{ $p->name }}</a>
                                        <div style="font-size:0.85rem; color:#6c757d; margin-top:0.25rem;">{{ $p->percent_done }}% — {{ $p->done_count }} / {{ $p->tasks_count }} tasks done</div>
                                    </div>
                                    <div style="text-align:right; min-width:100px;">
                                        <span class="badge bg-{{ $p->status === 'completed' ? 'success' : ($p->status === 'overdue' ? 'danger' : 'secondary') }} status-badge" style="transition:all 0.2s;">{{ ucfirst($p->status ?? 'active') }}</span>
                                    </div>
                                </div>
                                <div style="margin-top:0rem; background:#e9ecef; border-radius:6px; height:8px; overflow:hidden;">
                                    <div style="width:{{ $p->percent_done }}%; height:8px; background:linear-gradient(135deg,rgb(37, 4, 39) 0%, #764ba2 100%);"></div>
                                </div>
                                <div style="display:flex; flex-wrap:wrap; gap:1rem; font-size:0.85rem;">
                                    @if($p->priority)
                                    <div style="display:flex; align-items:center; gap:0.4rem;">
                                        <i class="fas fa-flag" style="color:#764ba2;"></i>
                                        <span style="font-weight:600; background:{{ in_array($p->priority, ['urgent', 'high']) ? '#f8d7da' : '#d1e7dd' }}; padding:0.2rem 0.6rem; border-radius:4px; {{ in_array($p->priority, ['urgent', 'high']) ? 'color:#842029' : 'color:#0f5132' }};font-size:0.75rem;">{{ ucfirst($p->priority) }}</span>
                                    </div>
                                    @endif
                                    @if($p->start_date)
                                    <div style="display:flex; align-items:center; gap:0.4rem; color:#495057;">
                                        <i class="fas fa-calendar-alt" style="color:#764ba2;"></i>
                                        <st>Start: {{ $p->start_date->format('M d, Y') }}</span>
                                    </div>
                                    @endif
                                    @if($p->deadline)
                                    <div style="display:flex; align-items:center; gap:0.4rem; color:#495057;">
                                        <i class="fas fa-calendar-check" style="color:#764ba2;"></i>
                                        <span>Due: {{ $p->deadline->format('M d, Y') }}</span>
                                    </div>
                                    @endif
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
                <h5><i class="fas fa-bolt"></i> Quick Links</h5>
                <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                    @if(Auth::user()->isAdminOrCustomer())
                        <a href="{{ route('projects.index') }}" class="quick-link-btn">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <i class="fas fa-eye" style="font-size: 1.2rem; color: #1D809F;"></i>
                                <div style="text-align: left;">
                                    <div style="font-weight: 600; color: #212529;">View Projects</div>
                                    <small style="color: #6c757d;">Manage all projects</small>
                                </div>
                            </div>
                            <i class="fas fa-arrow-right" style="color: #adb5bd; margin-left: auto;"></i>
                        </a>
                        <a href="{{ route('projects.create') }}" class="quick-link-btn">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <i class="fas fa-plus-circle" style="font-size: 1.2rem; color: #28a745;"></i>
                                <div style="text-align: left;">
                                    <div style="font-weight: 600; color: #212529;">Create Project</div>
                                    <small style="color: #6c757d;">Start a new project</small>
                                </div>
                            </div>
                            <i class="fas fa-arrow-right" style="color: #adb5bd; margin-left: auto;"></i>
                        </a>
                    @endif
                </div>
            </div>
            
            <style>
                .quick-link-btn {
                    display: flex;
                    align-items: center;
                    padding: 1.25rem;
                    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                    border: 1px solid #e9ecef;
                    border-radius: 8px;
                    text-decoration: none;
                    color: inherit;
                    transition: all 0.3s ease;
                    cursor: pointer;
                }

                .quick-link-btn:hover {
                    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                    border-color: #1D809F;
                    box-shadow: 0 4px 12px rgba(29, 128, 159, 0.15);
                    transform: translateX(4px);
                }

                .quick-link-btn small {
                    font-size: 0.8rem;
                    display: block;
                    margin-top: 0.25rem;
                }
            </style>
        </div>


    </div>

    
    
    <script>
        // Chart.js initialization with data from dashboard
        const chartColors = {
            primary: '#1D809F',
            success: '#198754',
            warning: '#ffc107',
            danger: '#dc3545',
            secondary: '#6c757d',
            purple: '#764ba2'
        };

        // Wait for Chart.js to load
        function initializeCharts() {
            // Data from dashboard variables
            const totalProjects = {{ $totalProjects ?? 0 }};
            const projectCompleted = {{ $projectCompleted ?? 0 }};
            const projectInProgress = {{ $projectInProgress ?? 0 }};
            const projectOverdue = {{ $projectOverdue ?? 0 }};

            const activeProjects = totalProjects - projectCompleted;

            // Project data with task statuses
            window.projectsData = {!! json_encode($projects->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'todo' => $p->tasks->where('status', 'todo')->count(),
                    'in_progress' => $p->tasks->where('status', 'in_progress')->count(),
                    'in_review' => $p->tasks->where('status', 'in_review')->count(),
                    'done' => $p->tasks->where('status', 'done')->count()
                ];
            })->all()) !!};

            // Project Status Distribution Pie Chart (store globally)
            if (document.getElementById('projectStatusChart')) {
                const ctx1 = document.getElementById('projectStatusChart').getContext('2d');
                window.projectStatusChart = new Chart(ctx1, {
                    type: 'doughnut',
                    data: {
                        labels: ['Active', 'Completed', 'Overdue'],
                        datasets: [{
                            data: [activeProjects, projectCompleted, projectOverdue],
                            backgroundColor: [
                                chartColors.primary,
                                chartColors.success,
                                chartColors.danger
                            ],
                            borderColor: ['#fff', '#fff', '#fff'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    font: { size: 12, weight: '600' },
                                    color: '#495057'
                                }
                            }
                        }
                    }
                });
            }

            // Task Status Chart for selected project (store globally)
            window.taskStatusChart = null;
            const projectFilterSelect = document.getElementById('projectFilterSelect');
            
            function updateTaskStatusChart(projectId) {
                const project = window.projectsData.find(p => p.id == projectId);
                const ctx2 = document.getElementById('taskStatusChart');
                
                if (!ctx2) return;
                
                if (window.taskStatusChart) {
                    window.taskStatusChart.destroy();
                }
                
                if (!project) {
                    window.taskStatusChart = new Chart(ctx2.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: [],
                            datasets: []
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            }
                        }
                    });
                    return;
                }

                window.taskStatusChart = new Chart(ctx2.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['To Do', 'In Progress', 'In Review', 'Done'],
                        datasets: [{
                            label: 'Task Count',
                            data: [project.todo, project.in_progress, project.in_review, project.done],
                            backgroundColor: [
                                '#e9ecef',
                                chartColors.warning,
                                '#6c757d',
                                chartColors.success
                            ],
                            borderRadius: 6,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    font: { size: 11, weight: '600' },
                                    color: '#495057'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    color: '#6c757d'
                                },
                                grid: {
                                    color: '#e9ecef'
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#495057',
                                    font: { weight: '600' }
                                }
                            }
                        }
                    }
                });
            }

            // Expose updater so polling can refresh the task chart
            window.updateTaskStatusChart = updateTaskStatusChart;

            // Event listener for project filter
            if (projectFilterSelect) {
                projectFilterSelect.addEventListener('change', function() {
                    updateTaskStatusChart(this.value);
                });
                
                // Initialize with the first project if one is selected
                if (projectFilterSelect.value) {
                    updateTaskStatusChart(projectFilterSelect.value);
                }
            }
        }

        // Initialize charts when the page loads
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initializeCharts();
                // start polling after charts initialize
                startDashboardPolling();
            });
        } else {
            initializeCharts();
            startDashboardPolling();
        }
    </script>

    <script>
        // Polling: fetch overview data and update DOM + charts
        function startDashboardPolling() {
            // fetch immediately, then every 10 seconds
            fetchOverviewAndUpdate();
            if (window._dashboardPollInterval) {
                clearInterval(window._dashboardPollInterval);
            }
            window._dashboardPollInterval = setInterval(fetchOverviewAndUpdate, 10000);
            // expose starter so it can be triggered from other scripts or console
            window.startDashboardPolling = startDashboardPolling;
        }

        window.fetchOverviewAndUpdate = async function() {
            console.debug('fetchOverviewAndUpdate called');
            try {
                const resp = await fetch('/dashboard/overview', {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!resp.ok) {
                    const text = await resp.text().catch(() => '');
                    console.warn('Overview fetch failed', resp.status, text.substring(0, 200));
                    return;
                }

                const contentType = resp.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    const text = await resp.text().catch(() => '');
                    console.warn('Overview returned non-JSON response (maybe redirected to login):', text.substring(0, 200));
                    return;
                }

                const data = await resp.json();
                console.debug('Overview data received', data);

                // Update stat counters
                const totalEl = document.getElementById('totalProjectsCount');
                if (totalEl) totalEl.textContent = data.totalProjects ?? 0;
                const inPrEl = document.getElementById('inProgressCount');
                if (inPrEl) inPrEl.textContent = data.projectInProgress ?? 0;
                const compEl = document.getElementById('completedCount');
                if (compEl) compEl.textContent = data.projectCompleted ?? 0;
                const overEl = document.getElementById('overdueCount');
                if (overEl) overEl.textContent = data.projectOverdue ?? 0;

                // Update projects data used by charts
                window.projectsData = (data.projects || []).map(p => ({
                    id: p.id,
                    name: p.name,
                    todo: p.todo || 0,
                    in_progress: p.in_progress || 0,
                    in_review: p.in_review || 0,
                    done: p.done || 0
                }));

                // Update projectStatusChart
                if (window.projectStatusChart) {
                    const active = (data.totalProjects || 0) - (data.projectCompleted || 0);
                    window.projectStatusChart.data.datasets[0].data = [active, data.projectCompleted || 0, data.projectOverdue || 0];
                    window.projectStatusChart.update();
                }

                // Rebuild project select while preserving selection
                const sel = document.getElementById('projectFilterSelect');
                const prev = sel ? sel.value : null;
                if (sel) {
                    sel.innerHTML = '<option value="">-- Choose a project --</option>';
                    (data.projects || []).forEach((p, i) => {
                        const opt = document.createElement('option');
                        opt.value = p.id;
                        opt.textContent = p.name;
                        if (prev && prev == p.id) opt.selected = true;
                        else if (!prev && i === 0) opt.selected = true;
                        sel.appendChild(opt);
                    });
                }

                // Update task chart for selected project
                const selected = sel ? sel.value : (data.projects && data.projects[0] ? data.projects[0].id : null);
                if (window.updateTaskStatusChart) {
                    window.updateTaskStatusChart(selected);
                } else if (window.taskStatusChart && selected) {
                    const proj = window.projectsData.find(x => x.id == selected);
                    if (proj) {
                        window.taskStatusChart.data.datasets[0].data = [proj.todo, proj.in_progress, proj.in_review, proj.done];
                        window.taskStatusChart.update();
                    }
                }

            } catch (error) {
                console.error('Overview fetch error', error);
            }
        }
    </script>

    

    <script>
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
    </script>
</x-app-layout>
