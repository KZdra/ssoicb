<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Tambah User Baru') }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Daftarkan akun pengguna atau administrator baru ke dalam SSO ICB.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 shadow-xs transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                <span>Kembali ke Daftar User</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm font-bold text-slate-900">Informasi Akun & Data Pribadi</h2>
            </div>
            
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Fullname -->
                    <div>
                        <label for="fullname" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" id="fullname" name="fullname" value="{{ old('fullname') }}" required placeholder="Contoh: Ahmad Fauzi" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                        @error('fullname')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username / NIS -->
                    <div>
                        <label for="username" class="block text-xs font-bold text-slate-700 mb-1.5">Username / NIS <span class="text-rose-500">*</span></label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required placeholder="Contoh: 10293841" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                        @error('username')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="ahmad@example.com" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                        @error('email')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Telepon / WhatsApp</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="081234567890" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                        @error('phone')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-xs font-bold text-slate-700 mb-1.5">Role Pengguna <span class="text-rose-500">*</span></label>
                        <select id="role" name="role" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User / Siswa</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">Status Akun <span class="text-rose-500">*</span></label>
                        <select id="status" name="status" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Password <span class="text-rose-500">*</span></label>
                        <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                        @error('password')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Password <span class="text-rose-500">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password di atas" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                    </div>

                    <!-- Avatar -->
                    <div class="sm:col-span-2">
                        <label for="avatar" class="block text-xs font-bold text-slate-700 mb-1.5">Foto Profil / Avatar</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-300 rounded-xl cursor-pointer">
                        @error('avatar')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-500/20 cursor-pointer">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
