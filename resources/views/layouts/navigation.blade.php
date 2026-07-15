<nav class="navbar navbar-expand bg-white shadow-sm" style="height: 64px;">
    <div class="container-fluid px-md-4 px-3 d-flex align-items-center">
        <!-- Mobile Sidebar Toggle -->
        <button class="btn btn-light d-md-none me-2 border-0" type="button" id="open-sidebar" aria-label="Toggle Sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        </button>

        <a class="navbar-brand d-md-none brand-text m-0 p-0" href="{{ route('dashboard') }}">
            {{ config('app.name', 'SSO') }}
        </a>

        <!-- Spacer -->
        <div class="flex-grow-1"></div>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ms-auto d-flex align-items-center">
            <!-- Authentication Links -->
            @auth
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <span class="d-none d-md-inline">{{ Auth::user()->username }}</span>
                        @if(!Auth::user()->avatar)
                            <div class="bg-light text-primary rounded-circle d-md-none d-flex align-items-center justify-content-center ms-2" style="width: 32px; height: 32px; font-weight: 600;">
                                {{ strtoupper(substr(Auth::user()->fullname, 0, 1)) }}
                            </div>
                        @else
                            <img src="{{ url(Storage::url(auth()->user()->avatar)) }}" class="rounded-circle d-md-none ms-2" width="32" height="32" style="object-fit: cover;">
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="navbarDropdown">
                        <div class="px-3 py-2 border-bottom d-md-none">
                            <div class="fw-bold">{{ Auth::user()->fullname }}</div>
                            <div class="text-muted small">{{ Auth::user()->email }}</div>
                        </div>
                        <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            {{ __('My Profile') }}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                            {{ __('Log Out') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            @endauth
        </ul>
    </div>
</nav>
