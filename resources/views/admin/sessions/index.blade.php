<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Sesi Aktif Real-time') }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Pantau seluruh pengguna yang sedang aktif dan login ke sistem SSO saat ini.</p>
            </div>
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>{{ $sessions->count() }} Sesi Terkoneksi</span>
            </span>
        </div>
    </x-slot>

    <!-- Sessions Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Pengguna</th>
                        <th class="py-3.5 px-4">IP Address</th>
                        <th class="py-3.5 px-4">Perangkat & Browser</th>
                        <th class="py-3.5 px-4">Aktivitas Terakhir</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sessions as $session)
                        @php
                            $isCurrentSession = ($session->id === request()->session()->getId());
                            $agent = $session->user_agent ?? '';
                            $browser = 'Unknown';
                            if (str_contains($agent, 'Firefox')) $browser = 'Mozilla Firefox';
                            elseif (str_contains($agent, 'Chrome')) $browser = 'Google Chrome';
                            elseif (str_contains($agent, 'Safari')) $browser = 'Apple Safari';
                            elseif (str_contains($agent, 'Edge')) $browser = 'Microsoft Edge';
                            
                            $platform = 'Unknown';
                            if (str_contains($agent, 'Windows')) $platform = 'Windows';
                            elseif (str_contains($agent, 'Macintosh')) $platform = 'macOS';
                            elseif (str_contains($agent, 'Linux')) $platform = 'Linux';
                            elseif (str_contains($agent, 'Android')) $platform = 'Android';
                            elseif (str_contains($agent, 'iPhone')) $platform = 'iOS';
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors {{ $isCurrentSession ? 'bg-indigo-50/30' : '' }}">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    @if($session->fullname)
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-cyan-500 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-xs">
                                            {{ strtoupper(substr($session->fullname, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-slate-900 text-xs">{{ $session->fullname }}</span>
                                                @if($isCurrentSession)
                                                    <span class="px-1.5 py-0.2 text-[9px] font-bold bg-indigo-600 text-white rounded">Sesi Anda</span>
                                                @endif
                                            </div>
                                            <div class="text-[10px] text-slate-400 font-mono">{{ $session->email }}</div>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 font-bold flex items-center justify-center text-xs shrink-0">
                                            ?
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-700 text-xs">Tamu / Guest</div>
                                            <div class="text-[10px] text-slate-400">Belum login</div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <code class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200/80 text-slate-800 font-mono text-[11px]">{{ $session->ip_address }}</code>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-semibold">{{ $browser }}</span>
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[10px] font-semibold">{{ $platform }}</span>
                                </div>
                                <div class="text-[10px] text-slate-400 truncate max-w-xs mt-0.5" title="{{ $session->user_agent }}">{{ $session->user_agent }}</div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="text-slate-800 font-semibold text-[11px]">{{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}</div>
                                <div class="text-[10px] text-slate-400">{{ date('d M Y, H:i', $session->last_activity) }}</div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-right">
                                <form action="{{ route('admin.sessions.destroy', $session->id) }}" method="POST" data-confirm="Putus koneksi sesi ini? Pengguna akan otomatis dikeluarkan dari sistem SSO." data-title="Terminasi Sesi Pengguna" data-danger="true" data-confirm-text="Ya, Putus Sesi!">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-600 hover:text-white border border-rose-200 rounded-xl transition-colors cursor-pointer">
                                        Terminasi
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Tidak ada sesi aktif saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
