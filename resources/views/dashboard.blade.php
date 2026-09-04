<x-app-layout>
    <div x-data="{ showImportModal: false }">
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Dashboard Overview</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Selamat datang kembali, <strong class="text-slate-800">{{ auth()->user()->fullname }}</strong>. Berikut ringkasan performa dan keamanan sistem SSO.</p>
                </div>
                @if(auth()->user()->role === 'admin')
                <div class="flex items-center gap-2.5">
                    <button type="button" @click="showImportModal = true" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 active:scale-95 transition-all shadow-sm shadow-emerald-500/20 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        <span>Import Excel</span>
                    </button>
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 transition-all shadow-sm shadow-indigo-500/20 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Tambah User</span>
                    </a>
                </div>
                @endif
            </div>
        </x-slot>

        @if(auth()->user()->role === 'admin')
            <!-- Admin 4 KPI Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Users Card -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 to-indigo-600"></div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Total Pengguna</span>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalUsers) }}</div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span><strong class="text-emerald-600 font-bold">{{ $activeUsers }}</strong> Aktif</span>
                        <span><strong class="text-slate-600 font-bold">{{ $inactiveUsers }}</strong> Nonaktif</span>
                        <span><strong class="text-rose-600 font-bold">{{ $adminUsers }}</strong> Admin</span>
                    </div>
                </div>

                <!-- Client Applications Card -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Aplikasi Klien</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalClients) }}</div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-500"><strong class="text-emerald-600 font-bold">{{ $activeClients }}</strong> Terhubung</span>
                        <a href="{{ route('admin.clients.index') }}" class="font-bold text-emerald-600 hover:text-emerald-700">Kelola &rarr;</a>
                    </div>
                </div>

                <!-- Active Sessions Card -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-amber-600"></div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Sesi Aktif</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($activeSessionsCount) }}</div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="flex items-center gap-1.5 text-slate-500"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Online</span>
                        <a href="{{ route('admin.sessions.index') }}" class="font-bold text-amber-600 hover:text-amber-700">Detail &rarr;</a>
                    </div>
                </div>

                <!-- Audit Logs Card -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-sky-500 to-sky-600"></div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Log Keamanan</span>
                        <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalLogs) }}</div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-500"><strong class="text-sky-600 font-bold">{{ $todayLogins }}</strong> Login Hari Ini</span>
                        <a href="{{ route('admin.audit_logs.index') }}" class="font-bold text-sky-600 hover:text-sky-700">Lihat Log &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                <a href="{{ route('admin.users.create') }}" class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-xs flex items-center gap-3 hover:border-indigo-300 hover:shadow-sm transition-all group">
                    <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">Tambah User</div>
                        <div class="text-[11px] text-slate-400">Input user baru</div>
                    </div>
                </a>

                <a href="{{ route('admin.users.template') }}" class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-xs flex items-center gap-3 hover:border-emerald-300 hover:shadow-sm transition-all group">
                    <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">Template Excel</div>
                        <div class="text-[11px] text-slate-400">Unduh .xlsx</div>
                    </div>
                </a>

                <a href="{{ route('admin.clients.create') }}" class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-xs flex items-center gap-3 hover:border-amber-300 hover:shadow-sm transition-all group">
                    <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900 group-hover:text-amber-600 transition-colors">Daftar Client</div>
                        <div class="text-[11px] text-slate-400">Integrasi OAuth</div>
                    </div>
                </a>

                <a href="{{ route('admin.sessions.index') }}" class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-xs flex items-center gap-3 hover:border-sky-300 hover:shadow-sm transition-all group">
                    <div class="w-9 h-9 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900 group-hover:text-sky-600 transition-colors">Pantau Sesi</div>
                        <div class="text-[11px] text-slate-400">Sesi user aktif</div>
                    </div>
                </a>
            </div>

            <!-- Two Columns Section -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Left: Recent Activity Feed & Recent Users -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Activity Feed -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                                </div>
                                <h2 class="text-sm font-bold text-slate-900">Aktivitas Sistem & Otentikasi Terbaru</h2>
                            </div>
                            <a href="{{ route('admin.audit_logs.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Semua Log &rarr;</a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                        <th class="py-3 px-4">Pengguna</th>
                                        <th class="py-3 px-4">Aksi</th>
                                        <th class="py-3 px-4">Keterangan</th>
                                        <th class="py-3 px-4">IP Address</th>
                                        <th class="py-3 px-4">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($recentLogs as $log)
                                        <tr class="hover:bg-slate-50/60 transition-colors">
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2">
                                                    @if($log->user && $log->user->avatar)
                                                        <img src="{{ url(\Illuminate\Support\Facades\Storage::url($log->user->avatar)) }}" class="w-7 h-7 rounded-full object-cover border border-slate-200">
                                                    @else
                                                        <div class="w-7 h-7 rounded-full bg-slate-100 text-indigo-600 font-bold flex items-center justify-center text-[10px]">
                                                            {{ strtoupper(substr($log->user->fullname ?? 'S', 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="font-bold text-slate-900 truncate max-w-[120px]">{{ $log->user->fullname ?? 'System' }}</div>
                                                        <div class="text-[10px] text-slate-400 font-mono">{{ $log->user->username ?? 'guest' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
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
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeStyle }}">
                                                    {{ $log->action }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="text-slate-600 truncate max-w-[180px]" title="{{ $log->description }}">{{ $log->description }}</div>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                <code class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px]">{{ $log->ip_address }}</code>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap text-slate-400 text-[11px]">
                                                {{ $log->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-6 text-center text-slate-400">Belum ada aktivitas tercatat.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recent Users -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                </div>
                                <h2 class="text-sm font-bold text-slate-900">Pengguna Terdaftar Terbaru</h2>
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Lihat Semua &rarr;</a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                        <th class="py-3 px-4">Nama Lengkap</th>
                                        <th class="py-3 px-4">Username / NIS</th>
                                        <th class="py-3 px-4">Email</th>
                                        <th class="py-3 px-4">Role</th>
                                        <th class="py-3 px-4">Status</th>
                                        <th class="py-3 px-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($recentUsers as $u)
                                        <tr class="hover:bg-slate-50/60 transition-colors">
                                            <td class="py-3 px-4 font-bold text-slate-900">{{ $u->fullname }}</td>
                                            <td class="py-3 px-4">
                                                <code class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px]">{{ $u->username }}</code>
                                            </td>
                                            <td class="py-3 px-4 text-slate-500">{{ $u->email }}</td>
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $u->role === 'admin' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-indigo-50 text-indigo-700 border border-indigo-200' }}">
                                                    {{ ucfirst($u->role) }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $u->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                                    {{ ucfirst($u->status) }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-right">
                                                <a href="{{ route('admin.users.edit', $u->id) }}" class="inline-flex p-1.5 rounded-lg text-indigo-600 hover:bg-indigo-50 border border-indigo-100 transition-colors" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right: Client Apps & Server Health -->
                <div class="space-y-6">
                    <!-- Connected Client Apps -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900">Aplikasi Klien Terhubung</h3>
                            </div>
                            <a href="{{ route('admin.clients.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Kelola</a>
                        </div>

                        <div class="space-y-2.5">
                            @forelse($clientApps as $client)
                                <div class="p-3 rounded-xl border border-slate-200/70 bg-slate-50/70 flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-slate-900 truncate">{{ $client->name }}</div>
                                        <code class="text-[10px] text-slate-400 block truncate">{{ is_array($client->redirect_uris) ? implode(', ', $client->redirect_uris) : ($client->redirect ?? 'N/A') }}</code>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold shrink-0 {{ $client->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-4 text-xs text-slate-400">Belum ada aplikasi klien terdaftar.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Server Health Widget -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900">Status Server SSO</h3>
                        </div>

                        <div class="space-y-2.5 text-xs">
                            <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                                <span class="text-slate-500">Protokol SSO</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">OAuth2 Passport</span>
                            </div>
                            <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                                <span class="text-slate-500">Database Connection</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Connected</span>
                            </div>
                            <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                                <span class="text-slate-500">PHP Version</span>
                                <span class="font-mono font-semibold text-slate-800">{{ $systemInfo['php_version'] }}</span>
                            </div>
                            <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                                <span class="text-slate-500">Laravel Framework</span>
                                <span class="font-mono font-semibold text-slate-800">v{{ $systemInfo['laravel_version'] }}</span>
                            </div>
                            <div class="flex items-center justify-between py-1.5">
                                <span class="text-slate-500">Waktu Server</span>
                                <span class="font-medium text-slate-800">{{ $systemInfo['server_time'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alpine.js Excel Import Modal -->
            <div 
                x-show="showImportModal" 
                class="fixed inset-0 z-50 overflow-y-auto"
                style="display: none;">
                
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showImportModal = false"></div>

                <div class="min-h-screen flex items-center justify-center p-4">
                    <div class="relative bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 z-10">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-900">Import User Massal Excel</h3>
                            </div>
                            <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>

                        <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                            @csrf
                            
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-xs font-bold text-slate-900">Belum punya template Excel?</div>
                                    <div class="text-[11px] text-slate-500">Unduh format resmi .xlsx siap pakai.</div>
                                </div>
                                <a href="{{ route('admin.users.template') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    <span>Download .xlsx</span>
                                </a>
                            </div>

                            <div>
                                <label for="file" class="block text-xs font-bold text-slate-700 mb-1">Pilih File Excel (.xlsx, .xls, .csv)</label>
                                <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv" required class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-300 rounded-xl cursor-pointer">
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                                <button type="button" @click="showImportModal = false" class="px-4 py-2 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Batal</button>
                                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-colors shadow-sm shadow-emerald-500/20">Mulai Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @else
            <!-- Regular User Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- User Profile Card -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 text-center">
                    <div class="mb-4">
                        @if(auth()->user()->avatar)
                            <img src="{{ url(\Illuminate\Support\Facades\Storage::url(auth()->user()->avatar)) }}" class="w-20 h-20 rounded-full mx-auto object-cover border-2 border-indigo-100 shadow-sm">
                        @else
                            <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-indigo-600 to-cyan-500 text-white font-bold text-2xl flex items-center justify-center mx-auto shadow-md shadow-indigo-500/20">
                                {{ strtoupper(substr(auth()->user()->fullname, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900">{{ auth()->user()->fullname }}</h3>
                    <div class="text-xs text-slate-500 mt-0.5">{{ auth()->user()->email }}</div>
                    <div class="mt-2.5">
                        <span class="px-2.5 py-0.5 text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-full">
                            {{ ucfirst(auth()->user()->role) }} Account
                        </span>
                    </div>

                    <div class="mt-5 p-3 rounded-xl bg-slate-50 border border-slate-200/70 text-left space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Username / NIS:</span>
                            <span class="font-mono font-bold text-slate-900">{{ auth()->user()->username }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">No. HP:</span>
                            <span class="font-semibold text-slate-900">{{ auth()->user()->phone ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Status Akun:</span>
                            <span class="font-bold text-emerald-600">Active</span>
                        </div>
                    </div>

                    <div class="mt-5">
                        <a href="{{ route('profile.edit') }}" class="block w-full py-2 px-4 rounded-xl text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-colors">
                            Edit Profil & Keamanan
                        </a>
                    </div>
                </div>

                <!-- Right 2 Columns: Client Apps & Login History -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <h4 class="text-sm font-bold text-slate-900 mb-2">Aplikasi yang Terhubung dengan SSO Anda</h4>
                        <p class="text-xs text-slate-500 mb-4">Akun SSO Anda dapat digunakan untuk login otomatis ke seluruh aplikasi internal berikut:</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @forelse($availableClients as $app)
                                <div class="p-3.5 rounded-xl border border-slate-200/70 bg-slate-50/70">
                                    <div class="text-xs font-bold text-slate-900">{{ $app->name }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ Str::limit($app->description ?? 'Aplikasi terintegrasi SSO ICB', 50) }}</div>
                                </div>
                            @empty
                                <div class="col-span-2 text-center py-4 text-xs text-slate-400">Belum ada aplikasi yang dikonfigurasi.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <h4 class="text-sm font-bold text-slate-900 mb-3">Riwayat Login Terakhir</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-bold text-[10px] uppercase">
                                        <th class="py-2">Aksi</th>
                                        <th class="py-2">IP Address</th>
                                        <th class="py-2 text-right">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($userLogs as $ulog)
                                        <tr>
                                            <td class="py-2.5"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">{{ $ulog->action }}</span></td>
                                            <td class="py-2.5 font-mono text-slate-600">{{ $ulog->ip_address }}</td>
                                            <td class="py-2.5 text-right text-slate-400">{{ $ulog->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-4 text-center text-slate-400">Belum ada riwayat aktivitas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
