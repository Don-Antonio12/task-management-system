<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ config('app.name', 'TMS') }} - Dashboard</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="{{ asset('build/assets/favicon.ico') }}" />
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="{{ asset('build/assets/favicon.ico') }}" />
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <style>
            body.dashboard-body {
                background: #f8f9fa;
            }
            .dashboard-header {
                color: black;
                padding: 2rem 0;
            }
            .dashboard-header h1 {
                margin: 0;
                font-size: 2rem;
                font-weight: 700;
            }
            .dashboard-content {
                padding: 2rem 0;
            }
            .dashboard-card {
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                padding: 1.5rem;
                margin-bottom: 1.5rem;
                transition: box-shadow 0.3s ease;
            }
            .dashboard-card:hover {
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            }
            .dashboard-card h3 {
                color: #1D809F;
                margin-top: 0;
                font-weight: 700;
            }
        </style>
    </head>
    <body id="page-top" class="dashboard-body">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <div class="dashboard-header">
                <div class="@if(request()->routeIs('developer.*', 'projects.*', 'tasks.*')) container-fluid px-4 px-lg-5 @else container px-4 px-lg-5 @endif">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <!-- Page Content -->
        <main class="dashboard-content">
            <div class="@if(request()->routeIs('developer.*', 'projects.*', 'tasks.*')) container-fluid px-4 px-lg-5 @else container px-4 px-lg-5 @endif">
                {{ $slot }}
            </div>
        </main>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

        @include('components.modals')

        <script>
            // Global status button handler (AJAX + board/badge UI updates)
            (function () {
                if (window.TMS_STATUS_HANDLER) return;
                window.TMS_STATUS_HANDLER = true;

                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

                const statusMap = {
                    todo: { label: 'To Do', cls: 'status-todo' },
                    in_progress: { label: 'In Progress', cls: 'status-in_progress' },
                    in_review: { label: 'In Review', cls: 'status-in_review' },
                    done: { label: 'Done', cls: 'status-done' },
                };

                function closest(el, sel) {
                    return el && el.closest ? el.closest(sel) : null;
                }

                function updateShowBadge(status) {
                    const badge = document.querySelector('.status-badge');
                    if (!badge || !statusMap[status]) return;
                    badge.className = 'status-badge ' + statusMap[status].cls;
                    badge.innerHTML = '<i class="fas fa-check-circle"></i> ' + statusMap[status].label;
                }

                function moveCardAndUpdateCounts(taskId, status, clickedBtn) {
                    // Supported card selectors (boards)
                    const card =
                        (clickedBtn ? closest(clickedBtn, '.card') : null) ||
                        document.querySelector('.card[data-task-id="' + taskId + '"]') ||
                        document.querySelector('.task-card[data-task-id="' + taskId + '"]');
                    if (!card) return;

                    // Supported target columns:
                    // - developer/tasks: .column-<status>
                    // - projects/show: .column-<status>
                    // - tasks/index (trello): .column-status-<status> .tasks-container (no buttons currently, but safe)
                    const target =
                        document.querySelector('.column-' + status) ||
                        document.querySelector('.column-status-' + status + ' .tasks-container') ||
                        document.querySelector('[data-status="' + status + '"]');

                    if (target) target.appendChild(card);

                    // Update counts if present
                    document.querySelectorAll('.column').forEach(col => {
                        const countSpan = col.querySelector('h4 span');
                        if (countSpan) countSpan.textContent = col.querySelectorAll('.card').length;
                    });
                    document.querySelectorAll('.trello-column').forEach(col => {
                        const countEl = col.querySelector('.trello-column-count');
                        const container = col.querySelector('.tasks-container');
                        if (countEl && container) countEl.textContent = container.querySelectorAll('.task-card').length;
                    });
                }

                document.addEventListener('click', async function (e) {
                    const btn = e.target && e.target.closest ? e.target.closest('.status-btn') : null;
                    if (!btn) return;

                    e.preventDefault();
                    e.stopPropagation();

                    const taskId = btn.getAttribute('data-task');
                    const status = btn.getAttribute('data-status');
                    if (!taskId || !status) return;

                    const card = closest(btn, '.card');
                    const buttons = card ? Array.from(card.querySelectorAll('.status-btn')) : Array.from(document.querySelectorAll('.status-btn[data-task="' + taskId + '"]'));
                    buttons.forEach(b => b.disabled = true);

                    try {
                        const res = await fetch('/tasks/' + taskId + '/status', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ status })
                        });

                        if (!res.ok) throw new Error('HTTP ' + res.status);
                        const data = await res.json();
                        if (!data || !data.success) throw new Error((data && data.message) ? data.message : 'Failed');

                        updateShowBadge(status);
                        moveCardAndUpdateCounts(taskId, status, btn);
                    } catch (err) {
                        console.error(err);
                        (window.TMS && window.TMS.showErrorModal) ? TMS.showErrorModal('Failed to update status: ' + (err.message || 'error')) : alert('Failed to update status: ' + (err.message || 'error'));
                    } finally {
                        buttons.forEach(b => b.disabled = false);
                    }
                }, { capture: true });
            })();
        </script>
  </body>
</html>
