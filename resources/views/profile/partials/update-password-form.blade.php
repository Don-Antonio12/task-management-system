<section class="profile-section">
    <div class="section-header">
        <h3><i class="fas fa-lock"></i> Update Password</h3>
        <p>Ensure your account is using a long, random password to stay secure. You must enter your current password to set a new one.</p>
    </div>

    <form method="post" action="{{ route('profile.password.update') }}" class="form-content">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="current_password" class="form-label">Current Password</label>
            <input id="current_password" name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" required autocomplete="current-password" placeholder="Enter your current password" />
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">New Password</label>
            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password" placeholder="Enter new password" />
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <p class="help-text">Minimum 8 characters.</p>
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required autocomplete="new-password" placeholder="Confirm new password" />
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-key"></i> Update Password
            </button>
        </div>
    </form>
</section>

<style>
    .profile-section .section-header { margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid rgba(29, 128, 159, 0.2); }
    .profile-section .section-header h3 { color: #1D809F; font-weight: 700; margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem; }
    .profile-section .section-header p { color: #adb5bd; margin: 0; font-size: 0.95rem; }
    .profile-section .form-content { margin-top: 1.5rem; }
    .profile-section .form-group { margin-bottom: 1.5rem; }
    .profile-section .form-label { font-weight: 600; color: #1D809F; font-size: 0.95rem; margin-bottom: 0.5rem; display: block; }
    .profile-section .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; }
    .profile-section .form-control:focus { border-color: #1D809F; box-shadow: 0 0 0 3px rgba(29, 128, 159, 0.1); outline: none; }
    .profile-section .form-control.is-invalid { border-color: #dc3545; }
    .profile-section .invalid-feedback { color: #dc3545; font-size: 0.85rem; margin-top: 0.25rem; display: block; }
    .profile-section .help-text { font-size: 0.85rem; color: #6c757d; margin-top: 0.4rem; }
    .profile-section .form-actions { display: flex; align-items: center; gap: 1rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(0, 0, 0, 0.05); }
    .profile-section .btn { padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: 0.95rem; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; }
    .profile-section .btn-primary { background: #1D809F; color: white; }
    .profile-section .btn-primary:hover { background: #155d74; }
</style>
