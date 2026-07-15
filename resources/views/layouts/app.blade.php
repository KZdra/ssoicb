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
<body>
    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    <div class="d-flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar p-3 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <span class="brand-text">{{ config('app.name', 'SSO') }}</span>
                <button type="button" class="btn-close d-md-none" id="close-sidebar" aria-label="Close"></button>
            </div>
            
            <nav class="nav flex-column mb-auto">
                <a href="{{ route('dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Dashboard
                </a>

                @if(auth()->user()->role === 'admin')
                <div class="text-uppercase text-muted fw-bold mt-4 mb-2 px-3" style="font-size: 0.7rem; letter-spacing: 0.05em;">Administration</div>
                
                <a href="{{ route('admin.users.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Users
                </a>
                
                <a href="{{ route('admin.clients.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    Client Applications
                </a>
                
                <a href="{{ route('admin.sessions.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    Active Sessions
                </a>

                <a href="{{ route('admin.audit_logs.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.audit_logs.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Audit Logs
                </a>
                @endif
            </nav>
            
            <div class="mt-auto pt-3 border-top">
                <div class="d-flex align-items-center px-2">
                    @if(auth()->user()->avatar)
                        <img src="{{ url(Storage::url(auth()->user()->avatar)) }}" alt="Avatar" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                    @else
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-weight: 600; font-size: 14px;">
                            {{ strtoupper(substr(auth()->user()->fullname, 0, 1)) }}
                        </div>
                    @endif
                    <div class="text-truncate" style="max-width: 150px;">
                        <div class="fw-semibold" style="font-size: 0.85rem;">{{ auth()->user()->fullname }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ auth()->user()->email }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-wrapper flex-grow-1 w-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="page-header shadow-sm">
                    <h1 class="h4 mb-0 text-dark fw-bold">
                        {{ $header }}
                    </h1>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="container-fluid px-md-4 px-3 py-4">
                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show d-flex align-items-center" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show d-flex align-items-center" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const openBtn = document.getElementById('open-sidebar');
            const closeBtn = document.getElementById('close-sidebar');

            function toggleSidebar() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }

            if(openBtn) openBtn.addEventListener('click', toggleSidebar);
            if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
            if(overlay) overlay.addEventListener('click', toggleSidebar);
        });
    </script>
</body>
</html>
