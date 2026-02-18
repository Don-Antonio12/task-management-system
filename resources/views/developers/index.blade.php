<x-app-layout>
    <x-slot name="header">
            <div style="padding: 1.5rem 2rem; border-radius: 12px; color: #000000; margin: -1rem -1rem 0 -1rem; border: 1px solid #000000;">
                <h1 style="margin:0; font-size:2rem; font-weight:700; color: #000000;">
                <i class="fas fa-tasks me-2"></i>{{ ucfirst($role) }} Tasks Dashboard
            </h1>
            <p style="margin: 0.5rem 0 0 0; color: #6c757d; font-size: 0.95rem;">Manage and track your assigned tasks</p>
        </div>
    </x-slot>

    <style>
        .board { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 1rem; 
            margin-top: 1.5rem; 
        }
        
        .column { 
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px; 
            padding: 1.2rem; 
            min-height: 400px; 
            border: 2px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .column:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.08);
        }
        
        .column h4 { 
            margin: 0 0 1rem 0; 
            display: flex; 
            align-items: center; 
            gap: 0.5rem;
            font-size: 1.1rem;
            color: #1a1a2e;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
        }
        
        .card { 
            background: white; 
            border-radius: 10px; 
            padding: 1rem; 
            margin-bottom: 1rem; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.08); 
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card:hover {
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }
        
        .card strong { 
            color: #1a1a2e;
            font-size: 1rem;
            display: block;
            margin-bottom: 0.4rem;
        }
        
        .card .meta { 
            color: #636e72; 
            font-size: 0.9rem;
            line-height: 1.4;
            margin-bottom: 0.4rem;
        }
        
        .card .actions { 
            margin-top: 0.9rem; 
            display: flex; 
            gap: 0.4rem; 
            flex-wrap: wrap; 
        }
        
        .status-btn {
            font-size: 0.85rem;
            padding: 0.35rem 0.7rem;
            transition: all 0.2s ease;
            border: none;
        }
        
        .status-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .status-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .badge-assigned { 
            background: linear-gradient(135deg, #e8f4f8 0%, #d0e8f0 100%);
            color: #1D809F; 
            padding: 0.35rem 0.7rem; 
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-block;
            margin-top: 0.4rem;
        }
        
        .task-status {
            display: inline-block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #667eea;
        }

        .project-details-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-top: 0.75rem;
            font-size: 0.9rem;
        }

        .project-detail {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            color: #6c757d;
        }

        .project-detail i {
            color: #1D809F;
            width: 16px;
        }

        .priority-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.6rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .priority-low { background: #d1e7dd; color: #0f5132; }
        .priority-medium { background: #fff3cd; color: #664d01; }
        .priority-high { background: #f8d7da; color: #842029; }
        .priority-urgent { background: #f69c6c; color: #fff; }

        .calendar-card {
            background: white;
            border-radius: 12px;
            padding: 1.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #1D809F;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.35rem;
            font-size: 0.9rem;
        }

        .calendar-day-header {
            text-align: center;
            font-weight: 600;
            color: #6c757d;
        }

        .calendar-day {
            text-align: center;
            padding: 0.4rem 0;
            border-radius: 6px;
            cursor: default;
        }

        .calendar-day.today {
            background: #1D809F;
            color: #fff;
            font-weight: 700;
        }

        .calendar-day.other-month {
            color: #ced4da;
        }
        
        @media (max-width: 1200px) {
            .board { grid-template-columns: repeat(2, 1fr); }
        }
        
        @media (max-width: 768px) {
            .board { grid-template-columns: 1fr; }
        }
    </style>

    @php
        $totalProjects = $projects->count();
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', 'done')->count();
        $inProgressTasks = $tasks->where('status', 'in_progress')->count();
    @endphp

    <div style="display:flex; gap:2rem; margin:1.5rem 0; flex-wrap:wrap; width:100%;">
        <div style="flex:1; min-width:360px; max-width:100%;">
            <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                <h5 style="margin:0 0 1rem 0; font-size:1.2rem; font-weight:700; color:#1D809F;">Overview</h5>
                <div style="display:flex; gap:1.5rem; flex-wrap:wrap;">
                    <div style="flex:1; min-width:140px; background:linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%); padding:1.25rem; border-radius:10px; text-align:center; border:1px solid #e9ecef;">
                        <div style="font-weight:700; font-size:2rem; color:#1D809F;">{{ $totalProjects }}</div>
                        <div style="color:#6c757d; font-size:0.95rem; margin-top:0.25rem;"><i class="fas fa-folder me-1"></i>Projects</div>
                    </div>
                    <div style="flex:1; min-width:140px; background:linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%); padding:1.25rem; border-radius:10px; text-align:center; border:1px solid #e9ecef;">
                        <div style="font-weight:700; font-size:2rem; color:#1D809F;">{{ $totalTasks }}</div>
                        <div style="color:#6c757d; font-size:0.95rem; margin-top:0.25rem;"><i class="fas fa-tasks me-1"></i>Tasks</div>
                    </div>
                    <div style="flex:1; min-width:140px; background:linear-gradient(135deg,#d1e7dd 0%,#badbcc 100%); padding:1.25rem; border-radius:10px; text-align:center; border:1px solid #a3cfbb;">
                        <div style="font-weight:700; font-size:2rem; color:#198754;">{{ $completedTasks }}</div>
                        <div style="color:#0f5132; font-size:0.95rem; margin-top:0.25rem;"><i class="fas fa-check-circle me-1"></i>Completed</div>
                    </div>
                </div>
            </div>

            <div style="margin-top:1.5rem;">
                <h5 style="margin-bottom:1rem; font-size:1.2rem; font-weight:700; color:#1D809F;">Projects Assigned</h5>
                @foreach($projects as $p)
                    <div style="background:white; padding:1.25rem; border-radius:12px; margin-bottom:1rem; box-shadow:0 2px 8px rgba(0,0,0,0.06); border:1px solid #f0f0f0;">
                        <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem;">
                            <div style="flex:1; min-width:0;">
                                <a href="{{ route('projects.show', $p) }}" style="font-weight:700; font-size:1.1rem; color:#1a1a2e; text-decoration:none;">{{ $p->name }}</a>
                                <div style="color:#6c757d; font-size:0.95rem; margin-top:0.35rem;">{{ Str::limit($p->description, 120) }}</div>
                            </div>
                            <div style="text-align:right; flex-shrink:0;">
                                <div style="font-size:0.95rem; font-weight:600; color:#6c757d;">{{ ucfirst($p->status ?? 'active') }}</div>
                                <div style="font-size:0.9rem; color:#6c757d;">{{ $p->assigned_total }} tasks</div>
                            </div>
                        </div>
                        <div style="margin-top:1rem; background:#e9ecef; border-radius:8px; height:10px; overflow:hidden;">
                            <div style="width:{{ $p->percent_done }}%; height:10px; background:linear-gradient(90deg,#667eea,#1D809F); border-radius:8px;"></div>
                        </div>
                        <div class="project-details-row">
                            @if($p->priority)
                            <div class="project-detail">
                                <i class="fas fa-flag"></i>
                                <span class="priority-badge priority-{{ $p->priority }}">{{ ucfirst($p->priority) }}</span>
                            </div>
                            @endif
                            @if($p->start_date)
                            <div class="project-detail">
                                <i class="fas fa-calendar-alt"></i>
                                <strong>Start:</strong> {{ $p->start_date->format('M d, Y') }}
                            </div>
                            @endif
                            @if($p->deadline)
                            <div class="project-detail">
                                <i class="fas fa-calendar-check"></i>
                                <strong>Due:</strong> {{ $p->deadline->format('M d, Y') }}
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="width:320px; min-width:280px; max-width:100%; flex-shrink:0;">
            <div class="calendar-card">
                <div class="calendar-header">
                    <span id="devCalendarMonth"></span>
                </div>
                <div class="calendar-grid" id="devCalendar"></div>
            </div>
        </div>

    <!-- Status update modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="statusModalLabel">Status Update</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="statusModalBody">Processing...</div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script>
        console.log('Developer page script loaded');

        // Simple calendar
        (function () {
            function renderCalendar(containerId, labelId) {
                const container = document.getElementById(containerId);
                const label = document.getElementById(labelId);
                if (!container || !label) return;

                const today = new Date();
                const year = today.getFullYear();
                const month = today.getMonth();

                const firstOfMonth = new Date(year, month, 1);
                const startWeekday = firstOfMonth.getDay();
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

                const totalCells = 42;
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
                    renderCalendar('devCalendar', 'devCalendarMonth');
                });
            } else {
                renderCalendar('devCalendar', 'devCalendarMonth');
            }
        })();

        // Initialize when DOM is ready
        function initStatusButtons() {
            console.log('Initializing status buttons...');
            
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';
            
            console.log('CSRF token:', csrf ? 'present' : 'missing');
            
            const statusBtns = document.querySelectorAll('.status-btn');
            console.log('Found', statusBtns.length, 'status buttons');
            
            // Initialize modal when Bootstrap is ready
            let statusModal = null;
            if (typeof window.bootstrap !== 'undefined') {
                const modalEl = document.getElementById('statusModal');
                statusModal = new window.bootstrap.Modal(modalEl, { backdrop: 'static' });
                console.log('Bootstrap Modal initialized');
            } else {
                console.warn('Bootstrap not loaded yet');
                return setTimeout(initStatusButtons, 100);
            }
            
            statusBtns.forEach((btn, index) => {
                btn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    console.log('Button clicked:', index);
                    
                    const taskId = this.getAttribute('data-task');
                    const status = this.getAttribute('data-status');
                    const card = this.closest('.card');
                    
                    console.log('Task ID:', taskId);
                    console.log('Status:', status);
                    
                    if (!taskId || !status) {
                        console.error('Missing data. TaskId:', taskId, 'Status:', status);
                        return;
                    }
                    
                    // Build URL dynamically
                    const url = '/tasks/' + taskId + '/status';
                    
                    // Disable buttons
                    card.querySelectorAll('.status-btn').forEach(b => b.disabled = true);
                    
                    // Show modal
                    const modalBody = document.getElementById('statusModalBody');
                    modalBody.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:0.5rem;"></i>Updating status...';
                    statusModal.show();
                    
                    try {
                        console.log('Sending PATCH request to:', url);
                        
                        const response = await fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ status: status })
                        });
                        
                        console.log('Response status:', response.status);
                        
                        if (!response.ok) {
                            throw new Error('HTTP error, status: ' + response.status);
                        }
                        
                        const data = await response.json();
                        console.log('Response data:', data);
                        
                        if (data.success) {
                            // Format status label
                            const statusLabel = status
                                .split('_')
                                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                                .join(' ');
                            
                            // Show success
                            modalBody.innerHTML = '<i class="fas fa-check-circle" style="color:green; margin-right:0.5rem;"></i>Status updated to <strong>' + statusLabel + '</strong>';
                            console.log('Status updated successfully');
                            
                            // Update card status display
                            const statusEl = card.querySelector('.task-status');
                            if (statusEl) {
                                statusEl.textContent = statusLabel;
                            }
                            
                            // Move card to new column
                            const columnClass = 'column-' + status;
                            const targetCol = document.querySelector('.' + columnClass);
                            console.log('Target column class:', columnClass);
                            console.log('Target column found:', !!targetCol);
                            
                            if (targetCol) {
                                targetCol.appendChild(card);
                                console.log('Card moved to new column');
                            }
                            
                            // Update column counts
                            document.querySelectorAll('.column').forEach(col => {
                                const countSpan = col.querySelector('h4 span');
                                if (countSpan) {
                                    const count = col.querySelectorAll('.card').length;
                                    countSpan.textContent = count;
                                }
                            });
                            
                            // Close modal after 1.5 seconds
                            setTimeout(() => {
                                statusModal.hide();
                                card.querySelectorAll('.status-btn').forEach(b => b.disabled = false);
                            }, 1500);
                        } else {
                            throw new Error(data.message || 'Failed to update status');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        modalBody.innerHTML = '<i class="fas fa-exclamation-triangle" style="color:orange; margin-right:0.5rem;"></i>Error: ' + error.message;
                        
                        // Re-enable buttons
                        card.querySelectorAll('.status-btn').forEach(b => b.disabled = false);
                    }
                });
            });
        }
        
        // Start initialization when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initStatusButtons);
        } else {
            // DOM is already ready
            setTimeout(initStatusButtons, 100);
        }
    </script>

</x-app-layout>
