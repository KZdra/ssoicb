<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TEKNIKA SSO') }} - Secure Authentication Gateway</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gradient-to-br from-slate-100 via-indigo-50/30 to-slate-200/70 antialiased font-sans flex items-center justify-center p-4 min-h-screen">
    <div class="w-full max-w-md my-8">
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <a href="/" class="inline-flex flex-col items-center group text-decoration-none">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-cyan-500 text-white flex items-center justify-center shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-transform duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <h1 class="mt-3.5 text-2xl font-extrabold text-slate-900 tracking-tight">{{ config('app.name', 'TEKNIKA') }}</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Central Identity & Access Gateway</p>
            </a>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-900/5 border border-slate-200/80 p-6 sm:p-8 backdrop-blur-md">
            {{ $slot }}
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6 space-y-1 text-xs text-slate-400">
            <div class="flex items-center justify-center gap-1.5 text-slate-500 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <span>256-Bit SSL Encrypted Connection</span>
            </div>
            <div>&copy; {{ date('Y') }} {{ config('app.name', 'TEKNIKA') }}. All rights reserved.</div>
        </div>
    </div>
</body>
</html>
