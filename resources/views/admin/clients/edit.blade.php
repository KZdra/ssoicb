<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Edit Aplikasi Klien: ') }} {{ $client->name }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui nama, status, atau redirect URL aplikasi terhubung.</p>
            </div>
            <a href="{{ route('admin.clients.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 shadow-xs transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                <span>Kembali</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-900">Perbarui Konfigurasi OAuth Klien</h2>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-400 font-mono">Client ID: {{ $client->id }}</span>
                </div>
            </div>
            
            <form action="{{ route('admin.clients.update', $client->id) }}" method="POST" class="p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- App Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Aplikasi <span class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $client->name) }}" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                        @error('name')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Redirect URI -->
                    <div class="sm:col-span-2">
                        <label for="redirect" class="block text-xs font-bold text-slate-700 mb-1.5">Redirect Callback URIs <span class="text-rose-500">*</span></label>
                        @php
                            $redirectVal = is_array($client->redirect_uris) ? implode(', ', $client->redirect_uris) : ($client->redirect ?? '');
                        @endphp
                        <input type="text" id="redirect" name="redirect" value="{{ old('redirect', $redirectVal) }}" required class="w-full px-3.5 py-2.5 text-xs font-mono bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                        <p class="text-[11px] text-slate-400 mt-1">Pisahkan beberapa URL dengan tanda koma (,).</p>
                        @error('redirect')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block text-xs font-bold text-slate-700 mb-1.5">Keterangan / Deskripsi Aplikasi</label>
                        <textarea id="description" name="description" rows="3" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">{{ old('description', $client->description) }}</textarea>
                        @error('description')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">Status Klien <span class="text-rose-500">*</span></label>
                        <select id="status" name="status" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                            <option value="active" {{ old('status', $client->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $client->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.clients.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-500/20 cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
