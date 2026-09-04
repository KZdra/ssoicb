<x-guest-layout>
    <div class="mb-5">
        @if (session('status'))
            <div class="p-3 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3 mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        @if(isset($clientApp) && $clientApp)
            <input type="hidden" name="client_id" value="{{ $clientApp->id }}">
            <div class="p-3.5 rounded-xl bg-indigo-50/80 border border-indigo-200/80 text-indigo-900 flex items-center gap-3 mb-5">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                </div>
                <div class="text-xs leading-snug">
                    Melanjutkan otentikasi login ke <strong class="font-bold text-indigo-950">{{ $clientApp->name }}</strong>.
                </div>
            </div>
        @endif

        <div class="text-center mb-6">
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Masuk ke Akun</h2>
            <p class="text-xs text-slate-500 mt-1">Masukkan username / NIS dan password akun Anda</p>
        </div>

        <!-- Username / NIS Input -->
        <div>
            <label for="username" class="block text-xs font-semibold text-slate-700 mb-1.5">Username / NIS</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <input 
                    id="username" 
                    name="username" 
                    type="text" 
                    value="{{ old('username') }}" 
                    required 
                    autofocus 
                    placeholder="Masukkan username atau NIS"
                    class="block w-full pl-10 pr-3.5 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
            </div>
        </div>

        <!-- Password Input with Toggle -->
        <div x-data="{ showPass: false }">
            <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </div>
                <input 
                    id="password" 
                    name="password" 
                    :type="showPass ? 'text' : 'password'" 
                    required 
                    placeholder="Masukkan password akun"
                    class="block w-full pl-10 pr-10 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                
                <button 
                    type="button" 
                    @click="showPass = !showPass" 
                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-hidden">
                    <svg x-show="!showPass" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    <svg x-show="showPass" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                </button>
            </div>
        </div>

        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] transition-all shadow-md shadow-indigo-500/20 cursor-pointer">
                <span>Masuk Sekarang</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
        </div>
    </form>
</x-guest-layout>
