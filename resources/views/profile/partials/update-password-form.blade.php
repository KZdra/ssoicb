<section>
    <header class="mb-6">
        <h2 class="text-base font-bold text-slate-900">
            {{ __('Perbarui Password') }}
        </h2>
        <p class="text-xs text-slate-500 mt-1">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak untuk menjaga keamanan.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4 max-w-xl">
        @csrf
        @method('put')

        <div>
            <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1.5">{{ __('Password Saat Ini') }}</label>
            <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
            @error('current_password', 'updatePassword')
                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password" class="block text-xs font-bold text-slate-700 mb-1.5">{{ __('Password Baru') }}</label>
            <input id="update_password" name="password" type="password" autocomplete="new-password" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
            @error('password', 'updatePassword')
                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">{{ __('Konfirmasi Password Baru') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
            @error('password_confirmation', 'updatePassword')
                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-2 flex items-center gap-3">
            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-500/20 cursor-pointer">
                {{ __('Ubah Password') }}
            </button>
            @if (session('status') === 'password-updated')
                <span class="text-xs font-bold text-emerald-600">{{ __('Password berhasil diperbarui.') }}</span>
            @endif
        </div>
    </form>
</section>
