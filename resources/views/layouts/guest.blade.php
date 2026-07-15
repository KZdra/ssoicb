<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12 col-md-8 col-lg-5 col-xl-4 py-5">
                <div class="text-center mb-4">
                    <a href="/" class="text-decoration-none d-inline-block">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3 shadow" style="width: 64px; height: 64px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                            </div>
                            <h1 class="h3 fw-bold text-dark mb-0" style="letter-spacing: -0.05em;">{{ config('app.name', 'Central') }} SSO</h1>
                            <p class="text-muted mt-1 small">Secure Authentication Gateway</p>
                        </div>
                    </a>
                </div>

                <div class="card shadow-lg border-0" style="border-radius: 1rem; overflow: hidden; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                    <div class="card-body p-4 p-md-5">
                        {{ $slot }}
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-muted small">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
