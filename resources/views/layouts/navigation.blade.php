<nav class="navbar navbar-expand-md" style="background: linear-gradient(135deg,rgb(37, 4, 39) 0%, #764ba2 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1); height: 100px; font-size: 1.5rem;">
    <div class="container-fluid px-4 px-lg-5"">
        <img src="{{ asset('build/assets/Logo.png') }}" alt="Task Management System" style="width: 80px; height: 80px; border-radius: 80%; margin-right: 1rem;">
        <a class="navbar-brand fw-bold" style="font-size: 1.8rem; color: white;" href="{{ route('dashboard') }}">Task Management System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: brightness(0);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-md-center" style="gap: 1.8rem;">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}" style="color: white;">Dashboard</a>
                </li>
                @if(Auth::user()->isAdminOrCustomer())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('projects.index') }}" style="color: white;">Projects</a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('developer.projects', ['role' => Auth::user()->role]) }}" style="color: white;">Projects</a>
                </li>
                @endif
                @php
                    $unreadCount = Auth::user()->unreadNotifications()->count();
                @endphp
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('notifications.index') }}" style="color: white; position:relative; display:flex; align-items:center; gap:0.25rem;">
                        <i class="fas fa-bell"></i>
                        @if($unreadCount > 0)
                            <span style="position:absolute; top:0; right:-0.4rem; background:#dc3545; color:white; border-radius:999px; padding:0 0.35rem; font-size:0.7rem; font-weight:700;">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                </li>
                <li class="nav-item dropdown" style="position: relative;">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="userDropdown" role="button" style="cursor: pointer; display: flex; align-items: center; gap: 0.5rem; color: white;">
                        <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu-custom" id="dropdownMenu" style="display: none; position: absolute; top: 100%; right: 0; background: white; min-width: 200px; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1000; margin-top: 0.5rem; padding: 0.5rem 0;">
                        <li style="padding: 0;"><a class="dropdown-item-custom" href="{{ route('profile.edit') }}" style="display: block; padding: 0.75rem 1.5rem; color: #333; text-decoration: none; transition: background 0.2s;"><i class="fas fa-user"></i> Profile</a></li>
                        <li style="padding: 0;"><hr style="margin: 0.5rem 0; border: none; border-top: 1px solid #e1e8ed;"></li>
                        <li style="padding: 0;">
                            <form method="POST" action="{{ route('logout') }}" style="padding: 0; margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item-custom" style="display: block; width: 100%; padding: 0.75rem 1.5rem; color: #333; text-decoration: none; background: none; border: none; cursor: pointer; text-align: left; transition: background 0.2s;">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>


<style>
    .dropdown-item-custom:hover {
        background-color: #f8f9fa;
    }
    
    #userDropdown {
        transition: opacity 0.2s ease;
    }
    
    #userDropdown:hover {
        opacity: 0.9;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userDropdown = document.getElementById('userDropdown');
        const dropdownMenu = document.getElementById('dropdownMenu');
        
        // Toggle dropdown on click
        userDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'block' : 'none';
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                dropdownMenu.style.display = 'none';
            }
        });
    });
</script>
