<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Aplikasi Klien Terhubung') }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola konfigurasi OAuth2 Client ID, Secret, dan Redirect URI untuk aplikasi eksternal.</p>
            </div>
            <a href="{{ route('admin.clients.create') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 transition-all shadow-sm shadow-indigo-500/20 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Daftarkan Klien Baru</span>
            </a>
        </div>
    </x-slot>

    <!-- Client Secret Warning Alert -->
    @if(session('raw_secret'))
        <div class="mb-6 p-5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
                <div class="flex-1 min-w-0" x-data="{ copied: false }">
                    <h3 class="text-sm font-bold text-amber-950">Simpan Client Secret Sekarang!</h3>
                    <p class="text-xs text-amber-800 mt-0.5">Secret ini hanya ditampilkan <strong>satu kali</strong> demi keamanan. Salin dan simpan pada file <code>.env</code> aplikasi klien Anda.</p>
                    <div class="mt-3 flex items-center gap-2">
                        <input type="text" readonly value="{{ session('raw_secret') }}" id="secretInput" class="w-full max-w-md px-3 py-2 text-xs font-mono bg-white border border-amber-300 rounded-xl text-slate-800">
                        <button type="button" @click="navigator.clipboard.writeText('{{ session('raw_secret') }}'); copied = true; setTimeout(() => copied = false, 2000)" class="px-3.5 py-2 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl transition-colors shrink-0">
                            <span x-text="copied ? 'Tersalin!' : 'Salin Secret'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Clients Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Nama Aplikasi</th>
                        <th class="py-3.5 px-4">Client ID</th>
                        <th class="py-3.5 px-4">Redirect URIs</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Tgl Registrasi</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($clients as $client)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900 text-xs">{{ $client->name }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">{{ Str::limit($client->description ?? '-', 45) }}</div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap" x-data="{ copied: false }">
                                <button type="button" @click="navigator.clipboard.writeText('{{ $client->id }}'); copied = true; setTimeout(() => copied = false, 1500)" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 hover:bg-slate-200 transition-colors font-mono text-[11px]" title="Klik untuk salin Client ID">
                                    <span x-text="copied ? 'Copied!' : '{{ $client->id }}'"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                </button>
                            </td>
                            <td class="py-3.5 px-4">
                                @if(is_array($client->redirect_uris))
                                    @foreach($client->redirect_uris as $uri)
                                        <code class="block text-[11px] font-mono text-slate-600 truncate max-w-xs">{{ $uri }}</code>
                                    @endforeach
                                @else
                                    <code class="text-[11px] font-mono text-slate-600 truncate max-w-xs block">{{ $client->redirect ?? 'N/A' }}</code>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $client->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    {{ ucfirst($client->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-400 text-[11px]">
                                {{ $client->created_at ? $client->created_at->format('d M Y') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.clients.edit', $client->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white border border-indigo-100 transition-colors" title="Edit Klien">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.clients.regenerate-secret', $client->id) }}" method="POST" data-confirm="Regenerasi Secret akan memutuskan koneksi aktif hingga aplikasi klien diperbarui dengan Secret baru. Lanjutkan?" data-title="Regenerasi Secret" data-icon="question" data-confirm-text="Ya, Regenerasi!">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white border border-amber-100 transition-colors cursor-pointer" title="Regenerasi Secret">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" data-confirm="Hapus aplikasi klien {{ $client->name }} secara permanen?" data-title="Hapus Klien" data-danger="true" data-confirm-text="Ya, Hapus!">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white border border-rose-100 transition-colors cursor-pointer" title="Hapus Klien">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                                    </div>
                                    <div class="font-bold text-slate-700 text-xs">Belum ada aplikasi klien terdaftar</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">Daftarkan aplikasi eksternal pertama Anda untuk memulai SSO.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
