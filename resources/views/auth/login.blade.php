<x-guest-layout>
    <h2>Welcome Back</h2>
    <p class="subtitle">Sign in to your Task Management System account</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="form-group">
            <label for="remember_me" style="display: flex; align-items: center; margin-bottom: 0; font-weight: 400;">
                <input id="remember_me" type="checkbox" name="remember" style="width: auto; margin-right: 0.5rem;">
                <span>Remember me</span>
            </label>
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn-login">Sign In</button>

        <!-- Links -->
        <div class="links">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <!-- Register Link -->
        <div class="register-link">
            <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
        </div>
    </form>
</x-guest-layout>
