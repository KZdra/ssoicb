<section class="space-y-4" x-data="{ confirmingUserDeletion: false }">
    <header>
        <h2 class="text-base font-bold text-rose-600">
            {{ __('Hapus Akun Anda') }}
        </h2>
        <p class="text-xs text-slate-500 mt-1">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan data yang terkait akan dihapus secara permanen.') }}
        </p>
    </header>

    <button 
        type="button" 
        @click="confirmingUserDeletion = true" 
        class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-colors shadow-xs cursor-pointer">
        {{ __('Hapus Akun') }}
    </button>

    <!-- Alpine Modal -->
    <div 
        x-show="confirmingUserDeletion" 
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="confirmingUserDeletion = false"></div>

        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 z-10">
                <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                    @csrf
                    @method('delete')

                    <h3 class="text-base font-bold text-slate-900">
                        {{ __('Konfirmasi Penghapusan Akun') }}
                    </h3>

                    <p class="text-xs text-slate-500">
                        {{ __('Apakah Anda yakin ingin menghapus akun? Masukkan password Anda untuk mengonfirmasi.') }}
                    </p>

                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">{{ __('Password') }}</label>
                        <input id="password" name="password" type="password" placeholder="{{ __('Password Anda') }}" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 transition-colors">
                        @error('password', 'userDeletion')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" @click="confirmingUserDeletion = false" class="px-4 py-2 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                            {{ __('Batal') }}
                        </button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-colors shadow-sm shadow-rose-500/20">
                            {{ __('Hapus Akun Sekarang') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
