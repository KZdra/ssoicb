<x-guest-layout>
    <div class="mb-4">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        @if(isset($clientApp) && $clientApp)
            <div class="alert alert-primary border-0 shadow-sm d-flex align-items-center mb-4" role="alert" style="background-color: rgba(79, 70, 229, 0.08); color: #4f46e5; border-radius: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 flex-shrink-0"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                <div style="font-size: 0.85rem; line-height: 1.4;">
                    Anda akan login ke <strong>{{ $clientApp->name }}</strong> menggunakan akun SSO.
                </div>
            </div>
        @endif

        <div class="mb-4 text-center">
            <h2 class="h4 fw-bold">Sign In</h2>
            <p class="text-muted small">Enter your credentials to access your account</p>
        </div>

        <!-- Username -->
        <div class="mb-4">
            <label for="username" class="form-label fw-medium">{{ __('Username') }}</label>
            <input id="username" class="form-control form-control-lg @error('username') is-invalid @enderror" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" placeholder="your_username">
            @error('username')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label fw-medium mb-0">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a class="text-decoration-none small text-primary" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <input id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-4 form-check">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
            <label class="form-check-label text-muted" for="remember_me">{{ __('Remember for 30 days') }}</label>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                {{ __('Sign In to Continue') }}
            </button>
        </div>
    </form>
</x-guest-layout>
