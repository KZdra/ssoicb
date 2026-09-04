<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TEKNIKA SSO') }} - Central Authentication Gateway</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased font-sans" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div 
            x-show="sidebarOpen" 
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false" 
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 lg:hidden"
            style="display: none;">
        </div>

        <!-- Full-Height Fixed Enterprise Sidebar -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#090d16] border-r border-slate-800/80 flex flex-col transition-transform duration-300 ease-in-out h-screen shrink-0">
            
            <!-- Brand Logo Header -->
            <div class="h-16 px-4 flex items-center justify-between border-b border-slate-800/60 shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-decoration-none">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-500 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-base text-white tracking-tight leading-none">{{ config('app.name', 'TEKNIKA') }}</span>
                            <span class="px-1.5 py-0.5 text-[10px] font-bold bg-indigo-500/20 text-indigo-300 rounded border border-indigo-500/30 uppercase tracking-wide">SSO</span>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">Central Auth Gateway</span>
                    </div>
                </a>
                <button type="button" @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            
            <!-- Navigation Links (Scrollable area) -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <div class="px-3 py-2 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Menu Utama</div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-600/20 text-white border border-indigo-500/30 shadow-xs' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ request()->routeIs('dashboard') ? 'text-indigo-400' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>Dashboard</span>
                </a>

                @if(auth()->user()->role === 'admin')
                <div class="pt-3 px-3 py-2 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Identitas & Aplikasi</div>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600/20 text-white border border-indigo-500/30 shadow-xs' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-indigo-400' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Kelola Pengguna</span>
                </a>
                
                <a href="{{ route('admin.clients.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.clients.*') ? 'bg-indigo-600/20 text-white border border-indigo-500/30 shadow-xs' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.clients.*') ? 'text-indigo-400' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    <span>Aplikasi Klien</span>
                </a>
                
                <div class="pt-3 px-3 py-2 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Keamanan & Log</div>

                <a href="{{ route('admin.sessions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.sessions.*') ? 'bg-indigo-600/20 text-white border border-indigo-500/30 shadow-xs' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.sessions.*') ? 'text-indigo-400' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    <span>Sesi Aktif</span>
                </a>

                <a href="{{ route('admin.audit_logs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.audit_logs.*') ? 'bg-indigo-600/20 text-white border border-indigo-500/30 shadow-xs' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.audit_logs.*') ? 'text-indigo-400' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    <span>Audit Logs</span>
                </a>
                @endif

                <div class="pt-3 px-3 py-2 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Pengaturan</div>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('profile.*') ? 'bg-indigo-600/20 text-white border border-indigo-500/30 shadow-xs' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ request()->routeIs('profile.*') ? 'text-indigo-400' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span>Profil Saya</span>
                </a>
            </nav>
            
            <!-- User Footer in Sidebar (Always pinned to bottom) -->
            <div class="p-3 bg-black/40 border-t border-slate-800/60 shrink-0 mt-auto">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5 min-w-0">
                        @if(auth()->user()->avatar)
                            <img src="{{ url(\Illuminate\Support\Facades\Storage::url(auth()->user()->avatar)) }}" alt="Avatar" class="w-8 h-8 rounded-full border border-slate-700 object-cover shrink-0">
                        @else
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-cyan-500 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                {{ strtoupper(substr(auth()->user()->fullname, 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-white truncate">{{ auth()->user()->fullname }}</div>
                            <div class="flex items-center gap-1">
                                <span class="px-1 py-0.2 text-[9px] font-bold rounded text-white {{ auth()->user()->role === 'admin' ? 'bg-rose-600' : 'bg-indigo-600' }}">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono truncate">{{ auth()->user()->username }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Logout Button -->
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();" class="text-slate-400 hover:text-rose-400 p-1.5 rounded-lg hover:bg-slate-800 transition-colors" title="Keluar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    </a>
                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area with Desktop Left Padding for Fixed Sidebar -->
        <div class="flex-1 flex flex-col min-w-0 lg:pl-64 min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading Banner -->
            @isset($header)
                <header class="bg-white border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 py-5">
                    <div class="max-w-7xl mx-auto">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto">
                    @if (session('success'))
                        <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between gap-3 shadow-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </div>
                                <div class="text-sm font-medium">{!! session('success') !!}</div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between gap-3 shadow-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-rose-500 text-white flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </div>
                                <div class="text-sm font-medium">{{ session('error') }}</div>
                            </div>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200/80 py-4 px-4 sm:px-6 lg:px-8 mt-auto">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
                    <div>&copy; {{ date('Y') }} <strong>{{ config('app.name', 'TEKNIKA') }}</strong> - Central Single Sign-On Gateway</div>
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1.5 font-medium text-slate-600">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> System Operational
                        </span>
                        <span>v1.0.0</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
