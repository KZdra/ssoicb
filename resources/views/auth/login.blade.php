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

        <div class="mb-4 text-center">
            <h2 class="h4 fw-bold">Sign In</h2>
            <p class="text-muted small">Enter your credentials to access your account</p>
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label fw-medium">{{ __('Email Address') }}</label>
            <input id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@company.com">
            @error('email')
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
