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
                min-height: 100vh;
                display: flex;
                flex-direction: column;
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
                flex: 1;
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

            .app-footer {
                margin-top: auto;
                background: linear-gradient(135deg, rgb(37, 4, 39) 0%, #764ba2 100%);
                color: rgba(255, 255, 255, 0.9);
                padding: 1.5rem 0;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.08);
            }
            .app-footer-inner {
                max-width: 100%;
                margin: 0 auto;
                padding: 0 1.5rem;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
            }
            .app-footer-brand {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }
            .app-footer-logo {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                object-fit: cover;
            }
            .app-footer-name {
                font-weight: 700;
                font-size: 1.1rem;
                color: #fff;
            }
            .app-footer-social {
                display: flex;
                align-items: center;
                gap: 1rem;
            }
            .app-footer-social a {
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: rgba(255, 255, 255, 0.9);
                background: rgba(255, 255, 255, 0.15);
                border-radius: 50%;
                text-decoration: none;
                font-size: 1rem;
                transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            }
            .app-footer-social a:hover {
                background: rgba(255, 255, 255, 0.25);
                color: #fff;
                transform: translateY(-2px);
            }
            .app-footer-copy {
                font-size: 0.85rem;
                opacity: 0.9;
            }
            @media (max-width: 768px) {
                .app-footer-inner {
                    flex-direction: column;
                    text-align: center;
                    gap: 1.25rem;
                }
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

        <!-- Footer -->
        <footer class="app-footer">
            <div class="container-fluid px-4 px-lg-5">
                <div class="app-footer-inner">
                    <div class="app-footer-brand">
                        <img src="{{ asset('build/assets/Logo.png') }}" alt="{{ config('app.name', 'TMS') }}" class="app-footer-logo">
                        <span class="app-footer-name">{{ config('app.name', 'Task Management System') }}</span>
                    </div>
                    <div class="app-footer-social">
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                    <div class="app-footer-copy">
                        &copy; {{ date('Y') }} {{ config('app.name', 'TMS') }}. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>

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
