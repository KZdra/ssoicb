<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Daftarkan Aplikasi Klien Baru') }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Integrasikan aplikasi eksternal (Presensi, LMS, SIM, dll.) menggunakan protokol OAuth2.</p>
            </div>
            <a href="{{ route('admin.clients.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 shadow-xs transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                <span>Kembali ke Daftar Klien</span>
            </a>
        </div>
    </x-slot>

    <!-- Error Validation Banner -->
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-xs">
            <div class="flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <div>
                    <div class="font-bold mb-1">Gagal Mendaftarkan Klien. Harap periksa input berikut:</div>
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm font-bold text-slate-900">Konfigurasi OAuth2 Client</h2>
            </div>
            
            <form action="{{ route('admin.clients.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- App Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Aplikasi <span class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Aplikasi Presensi Siswa ICB" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border @error('name') border-rose-500 bg-rose-50/20 @else border-slate-300 @enderror rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                        @error('name')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Redirect URI -->
                    <div class="sm:col-span-2">
                        <label for="redirect" class="block text-xs font-bold text-slate-700 mb-1.5">Redirect Callback URI <span class="text-rose-500">*</span></label>
                        <input type="text" id="redirect" name="redirect" value="{{ old('redirect') }}" required placeholder="http://presensi.test/callback atau https://presensi.sekolah.sch.id/sso/callback" class="w-full px-3.5 py-2.5 text-xs font-mono bg-slate-50 border @error('redirect') border-rose-500 bg-rose-50/20 @else border-slate-300 @enderror rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                        <p class="text-[11px] text-slate-400 mt-1">Alamat endpoint aplikasi Anda yang akan menerima authorization code setelah user login.</p>
                        @error('redirect')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block text-xs font-bold text-slate-700 mb-1.5">Keterangan / Deskripsi Aplikasi</label>
                        <textarea id="description" name="description" rows="3" placeholder="Sistem absensi dan monitoring kehadiran siswa SMK ICB" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">Status Klien <span class="text-rose-500">*</span></label>
                        <select id="status" name="status" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active (Dapat Digunakan)</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive (Nonaktif)</option>
                        </select>
                        @error('status')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.clients.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-98 transition-all shadow-sm shadow-indigo-500/20 cursor-pointer">Daftarkan Klien</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
