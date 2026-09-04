<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Audit Logs & Security Trail') }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Rekam jejak seluruh aktivitas otentikasi, perubahan data, dan event keamanan SSO.</p>
            </div>
            <form action="{{ route('admin.audit_logs.clear') }}" method="POST" data-confirm="Apakah Anda yakin ingin membersihkan seluruh log audit keamanan? Tindakan ini permanen dan tidak dapat dibatalkan." data-title="Bersihkan Semua Log" data-danger="true" data-confirm-text="Ya, Bersihkan!">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-600 hover:text-white border border-rose-200 transition-colors shadow-xs cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    <span>Bersihkan Log</span>
                </button>
            </form>
        </div>
    </x-slot>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs mb-6">
        <form action="{{ route('admin.audit_logs.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <div class="sm:col-span-6">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </div>
                    <input type="text" name="search" class="w-full pl-9 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors" placeholder="Cari aksi, IP address, atau detail..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="sm:col-span-4">
                <select name="action" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                    <option value="">-- Semua Jenis Aksi --</option>
                    <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                    <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
                    <option value="user.created" {{ request('action') === 'user.created' ? 'selected' : '' }}>User Created</option>
                    <option value="user.updated" {{ request('action') === 'user.updated' ? 'selected' : '' }}>User Updated</option>
                    <option value="user.deleted" {{ request('action') === 'user.deleted' ? 'selected' : '' }}>User Deleted</option>
                    <option value="user.bulk_import" {{ request('action') === 'user.bulk_import' ? 'selected' : '' }}>Excel Bulk Import</option>
                    <option value="client.create" {{ request('action') === 'client.create' ? 'selected' : '' }}>Client Created</option>
                </select>
            </div>
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-colors shadow-xs">Filter</button>
                @if(request('search') || request('action'))
                    <a href="{{ route('admin.audit_logs.index') }}" class="p-2 text-xs font-bold text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl border border-slate-200 transition-colors" title="Reset Filter">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Pengguna</th>
                        <th class="py-3.5 px-4">Aksi</th>
                        <th class="py-3.5 px-4">Keterangan</th>
                        <th class="py-3.5 px-4">IP Address</th>
                        <th class="py-3.5 px-4 text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    @if($log->user)
                                        <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-indigo-600 to-cyan-500 text-white font-bold flex items-center justify-center text-[10px] shrink-0">
                                            {{ strtoupper(substr($log->user->fullname, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-xs">{{ $log->user->fullname }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono">{{ $log->user->username }}</div>
                                        </div>
                                    @else
                                        <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 font-bold flex items-center justify-center text-[10px] shrink-0">
                                            ?
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-700 text-xs">System / Guest</div>
                                            <div class="text-[10px] text-slate-400">Anonym</div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @php
                                    $badgeStyle = match($log->action) {
                                        'login' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'logout' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'user.create', 'user.created', 'user.bulk_import' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'user.update', 'user.updated' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'user.delete', 'user.deleted' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'client.create', 'client.secret_regenerated' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        default => 'bg-slate-50 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeStyle }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-slate-700 max-w-sm" title="{{ $log->description }}">{{ $log->description }}</div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <code class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200/80 text-slate-800 font-mono text-[11px]">{{ $log->ip_address }}</code>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-right">
                                <div class="text-slate-800 font-semibold text-[11px]">{{ $log->created_at->diffForHumans() }}</div>
                                <div class="text-[10px] text-slate-400">{{ $log->created_at->format('d M Y, H:i:s') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Tidak ada data audit log yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-slate-500">
            <div>
                Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} log
            </div>
            <div>
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
