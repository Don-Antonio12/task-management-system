<x-guest-layout>
    <h2>Create Account</h2>
    <p class="subtitle">Join our Task Management System and start organizing your tasks</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Position / Role -->
        <div class="form-group">
            <label for="role">Position <span class="text-danger">*</span></label>
            <select id="role" name="role" class="form-select-role" required>
                <option value="">-- Select position --</option>
                <optgroup label="Developer">
                    <option value="backend" {{ old('role') == 'backend' ? 'selected' : '' }}>Backend Developer</option>
                    <option value="frontend" {{ old('role') == 'frontend' ? 'selected' : '' }}>Frontend Developer</option>
                    <option value="server" {{ old('role') == 'server' ? 'selected' : '' }}>Server Admin</option>
                </optgroup>
                <optgroup label="Customer">
                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                </optgroup>
            </select>
            <p class="form-hint">Choose Developer (Backend/Frontend/Server) or Customer. Customers use the admin dashboard.</p>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Register Button -->
        <button type="submit" class="btn-login">Create Account</button>

        <!-- Login Link -->
        <div class="register-link">
            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
        </div>
    </form>
</x-guest-layout>
