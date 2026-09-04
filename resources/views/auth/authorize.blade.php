<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 border border-indigo-100 shadow-xs">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 tracking-tight">Otorisasi Akses Aplikasi</h3>
        <p class="text-xs text-slate-500 mt-1">Aplikasi <strong class="text-slate-900">{{ $client->name }}</strong> meminta izin untuk mengakses akun SSO Anda.</p>
    </div>

    <!-- User Information Bar -->
    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 mb-5 flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-indigo-600 to-cyan-500 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-xs">
            {{ strtoupper(substr(auth()->user()->fullname, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <div class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->fullname }}</div>
            <div class="text-[11px] text-slate-400 font-mono truncate">{{ auth()->user()->email }}</div>
        </div>
        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 shrink-0">
            Login
        </span>
    </div>

    <!-- Scopes / Permissions List -->
    @if (count($scopes) > 0)
        <div class="mb-5">
            <div class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider mb-2">Izin yang Akan Diberikan:</div>
            <ul class="border border-slate-200 rounded-xl divide-y divide-slate-100 overflow-hidden text-xs">
                @foreach ($scopes as $scope)
                    <li class="bg-white p-3 flex items-center gap-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span class="text-slate-800">{{ $scope->description }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-xs text-slate-500 mb-5">
            <span class="inline-flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Aplikasi ini hanya membaca data identitas dasar Anda (nama, NIS, email).
            </span>
        </div>
    @endif

    <div class="space-y-2 mt-6">
        <!-- Approve Form -->
        <form method="POST" action="{{ route('passport.authorizations.approve') }}">
            @csrf
            <input type="hidden" name="state" value="{{ $request->state }}">
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">
            <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-98 transition-all shadow-md shadow-indigo-500/20 cursor-pointer">
                Izinkan & Lanjutkan Login
            </button>
        </form>

        <!-- Deny Form -->
        <form method="POST" action="{{ route('passport.authorizations.deny') }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="state" value="{{ $request->state }}">
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">
            <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 transition-colors cursor-pointer">
                Tolak Akses
            </button>
        </form>
    </div>
</x-guest-layout>
