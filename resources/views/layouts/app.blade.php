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
    <style>
        .sidebar {
            min-height: 100vh;
            width: 250px;
            background-color: #343a40;
            color: white;
            transition: all 0.3s;
        }
        .sidebar a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        .sidebar a:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            flex-grow: 1;
            min-width: 0;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-none d-md-block p-3">
            <h4 class="text-center py-2 border-bottom border-secondary">{{ config('app.name', 'SSO') }}</h4>
            <ul class="nav flex-column mt-3">
                <li class="nav-item mb-2">
                    <a href="{{ route('dashboard') }}" class="nav-link py-2 px-3 rounded {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : '' }}">
                        Dashboard
                    </a>
                </li>
                @if(auth()->user()->role === 'admin')
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.users.index') }}" class="nav-link py-2 px-3 rounded {{ request()->routeIs('admin.users.*') ? 'bg-primary text-white' : '' }}">
                        Users
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.clients.index') }}" class="nav-link py-2 px-3 rounded {{ request()->routeIs('admin.clients.*') ? 'bg-primary text-white' : '' }}">
                        Client Applications
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.sessions.index') }}" class="nav-link py-2 px-3 rounded {{ request()->routeIs('admin.sessions.*') ? 'bg-primary text-white' : '' }}">
                        Active Sessions
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm mb-4 py-3">
                    <div class="container-fluid px-4">
                        <h2 class="h5 mb-0 text-dark">
                            {{ $header }}
                        </h2>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="container-fluid px-4 pb-5">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
