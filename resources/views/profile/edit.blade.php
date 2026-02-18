<x-app-layout>
    <x-slot name="header">
        <h1 style="margin: 0; font-size: 2rem; font-weight: 700;">Profile Settings</h1>
    </x-slot>

    <style>
        .profile-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .profile-section h3 {
            color: #1D809F;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(29, 128, 159, 0.2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group label {
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.6rem;
            display: block;
            font-size: 0.95rem;
        }

        .form-control {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: #1D809F;
            box-shadow: 0 0 0 3px rgba(29, 128, 159, 0.1);
            outline: none;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: #1D809F;
            color: white;
        }

        .btn-primary:hover {
            background: #155d74;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(29, 128, 159, 0.3);
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #bb2d3b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .help-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.4rem;
        }

        .danger-zone {
            border: 2px solid #dc3545;
            border-radius: 8px;
            padding: 1.5rem;
            background: rgba(220, 53, 69, 0.05);
        }

        .danger-zone h4 {
            color: #dc3545;
            margin-top: 0;
        }
    </style>

    <div style="max-width: 900px; margin-top: 2rem;">
        @if (session('status') === 'profile-updated')
            <div class="success-message">
                <i class="fas fa-check-circle"></i> Profile updated successfully!
            </div>
        @endif
        @if (session('status') === 'password-updated')
            <div class="success-message">
                <i class="fas fa-check-circle"></i> Password updated successfully!
            </div>
        @endif

        <div class="profile-section">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="profile-section">
            @include('profile.partials.update-password-form')
        </div>

        <div class="profile-section danger-zone">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
