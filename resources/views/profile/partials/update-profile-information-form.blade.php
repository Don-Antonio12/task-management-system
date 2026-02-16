<section class="profile-section">
    <div class="section-header">
        <h3><i class="fas fa-user-edit"></i> Profile Information</h3>
        <p>Update your account's profile information and email address.</p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="form-content">
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="name" class="form-label">Name</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning mt-3">
                    <p class="mb-0">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="btn-text">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="alert-success mt-2">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-success">
                    {{ __('Saved successfully!') }}
                </span>
            @endif
        </div>
    </form>
</section>

<style>
    .profile-section {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .section-header {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(29, 128, 159, 0.2);
    }

    .section-header h3 {
        color: #1D809F;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.25rem;
    }

    .section-header p {
        color: #adb5bd;
        margin: 0;
        font-size: 0.95rem;
    }

    .form-content {
        margin-top: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #1D809F;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95rem;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #1D809F;
        box-shadow: 0 0 0 3px rgba(29, 128, 159, 0.1);
        outline: none;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.25rem;
        display: block;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid;
        font-size: 0.95rem;
    }

    .alert-warning {
        background: #fff3cd;
        border-left-color: #ffc107;
        color: #664d03;
    }

    .alert-success {
        color: #0f5132;
        font-weight: 600;
    }

    .btn-text {
        background: none;
        border: none;
        color: #1D809F;
        text-decoration: underline;
        cursor: pointer;
        font-weight: 600;
        padding: 0;
    }

    .btn-text:hover {
        color: #155d74;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: #1D809F;
        color: white;
    }

    .btn-primary:hover {
        background: #155d74;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(29, 128, 159, 0.2);
    }

    .text-success {
        color: #198754;
        font-weight: 600;
    }
</style>
