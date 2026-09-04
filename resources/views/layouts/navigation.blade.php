<nav class="h-16 bg-white border-b border-slate-200/80 sticky top-0 z-30 flex items-center">
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <!-- Left: Mobile Toggle & Brand/Status -->
        <div class="flex items-center gap-3">
            <button 
                type="button" 
                @click="sidebarOpen = true" 
                class="lg:hidden p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 border border-slate-200 focus:outline-hidden" 
                aria-label="Toggle Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>

            <!-- Brand on mobile -->
            <a class="lg:hidden font-bold text-slate-900 flex items-center gap-2" href="{{ route('dashboard') }}">
                <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <span class="text-sm font-extrabold">{{ config('app.name', 'TEKNIKA') }}</span>
            </a>

            <!-- System Live Status on Desktop -->
            <div class="hidden sm:flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-200/80 text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="font-semibold text-slate-600">SSO Engine: <strong class="text-emerald-600 font-bold">Online</strong></span>
                <span class="text-slate-300">|</span>
                <span class="text-slate-500 font-medium">{{ now()->format('D, d M Y') }}</span>
            </div>
        </div>

        <!-- Right Side: User Profile Dropdown -->
        <div class="flex items-center gap-3">
            @auth
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button 
                        type="button" 
                        @click="userMenuOpen = !userMenuOpen" 
                        @click.outside="userMenuOpen = false"
                        class="flex items-center gap-2 p-1 pl-2 pr-3 rounded-full border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-colors focus:outline-hidden">
                        
                        @if(Auth::user()->avatar)
                            <img src="{{ url(\Illuminate\Support\Facades\Storage::url(auth()->user()->avatar)) }}" class="w-7 h-7 rounded-full object-cover border border-slate-300">
                        @else
                            <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-indigo-600 to-cyan-500 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                {{ strtoupper(substr(Auth::user()->fullname, 0, 1)) }}
                            </div>
                        @endif

                        <div class="hidden md:flex flex-col text-left">
                            <span class="text-xs font-bold text-slate-900 leading-tight">{{ Auth::user()->fullname }}</span>
                            <span class="text-[10px] text-slate-500 capitalize leading-tight">{{ Auth::user()->role }}</span>
                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div 
                        x-show="userMenuOpen"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-slate-200 py-1.5 z-50 divide-y divide-slate-100"
                        style="display: none;">
                        
                        <div class="px-4 py-2.5">
                            <div class="text-xs font-bold text-slate-900">{{ Auth::user()->fullname }}</div>
                            <div class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</div>
                            <div class="mt-1">
                                <span class="px-1.5 py-0.5 text-[9px] font-bold rounded text-white {{ Auth::user()->role === 'admin' ? 'bg-rose-600' : 'bg-indigo-600' }}">
                                    {{ ucfirst(Auth::user()->role) }} Account
                                </span>
                            </div>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <span>Profil & Keamanan</span>
                            </a>

                            @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.sessions.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                <span>Pantau Sesi Aktif</span>
                            </a>
                            @endif
                        </div>

                        <div class="py-1">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('topbar-logout-form').submit();" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                <span>Keluar</span>
                            </a>

                            <form id="topbar-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>
