<section class="my-5">
    <header>
        <h2 class="h5 text-dark">
            {{ __('Update Password') }}
        </h2>
        <p class="text-muted">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('PUT')

        <!-- Current Password -->
        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">
                {{ __('Current Password') }}
            </label>
            <input 
                type="password" 
                class="form-control @error('current_password') is-invalid @enderror"
                id="update_password_current_password" 
                name="current_password" 
                autocomplete="current-password"
            >
            @error('current_password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- New Password -->
        <div class="mb-3">
            <label for="update_password_password" class="form-label">
                {{ __('New Password') }}
            </label>
            <input 
                type="password" 
                class="form-control @error('password') is-invalid @enderror"
                id="update_password_password" 
                name="password" 
                autocomplete="new-password"
            >
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">
                {{ __('Confirm Password') }}
            </label>
            <input 
                type="password" 
                class="form-control @error('password_confirmation') is-invalid @enderror"
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                autocomplete="new-password"
            >
            @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Save Button and Status Message -->
        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p class="text-success mb-0" id="status-message">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
 