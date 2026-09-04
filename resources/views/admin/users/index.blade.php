<x-app-layout>
    <div x-data="{ showImportModal: false }">
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('User Management') }}</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Kelola seluruh data akun pengguna, siswa, guru, dan administrator SSO ICB.</p>
                </div>
                <div class="flex items-center gap-2.5">
                    <button type="button" @click="showImportModal = true" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 active:scale-95 transition-all shadow-sm shadow-emerald-500/20 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        <span>Import Excel (.xlsx)</span>
                    </button>

                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 transition-all shadow-sm shadow-indigo-500/20 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Tambah User Baru</span>
                    </a>
                </div>
            </div>
        </x-slot>

        <!-- Import Errors Detail (if any) -->
        @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs shadow-xs">
                <div class="flex items-start gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <div>
                        <div class="font-bold mb-1">Catatan Import Baris yang Dilewati:</div>
                        <ul class="list-disc pl-4 space-y-0.5">
                            @foreach(session('import_errors') as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- User Counter Chips -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <div class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">TOTAL USERS</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $stats['total'] }}</div>
                </div>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <div class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">USER AKTIF</div>
                    <div class="text-2xl font-extrabold text-emerald-600 mt-0.5">{{ $stats['active'] }}</div>
                </div>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <div class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">NONAKTIF</div>
                    <div class="text-2xl font-extrabold text-amber-600 mt-0.5">{{ $stats['inactive'] }}</div>
                </div>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line></svg>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <div class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">ADMINISTRATOR</div>
                    <div class="text-2xl font-extrabold text-rose-600 mt-0.5">{{ $stats['admins'] }}</div>
                </div>
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs mb-6">
            <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <div class="sm:col-span-5">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                        <input type="text" name="search" class="w-full pl-9 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors" placeholder="Cari nama, NIS, email, atau no. HP..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="sm:col-span-3">
                    <select name="role" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                        <option value="">-- Semua Role --</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User / Siswa</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <select name="status" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                        <option value="">-- Semua Status --</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="sm:col-span-2 flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 px-3 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-colors shadow-xs">Filter</button>
                    @if(request('search') || request('role') || request('status'))
                        <a href="{{ route('admin.users.index') }}" class="p-2 text-xs font-bold text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl border border-slate-200 transition-colors" title="Reset Filter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 px-4">User Profil</th>
                            <th class="py-3.5 px-4">Username / NIS</th>
                            <th class="py-3.5 px-4">Email & Kontak</th>
                            <th class="py-3.5 px-4">Role</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Tgl Terdaftar</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        @if($user->avatar)
                                            <img src="{{ url(\Illuminate\Support\Facades\Storage::url($user->avatar)) }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 shrink-0">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-cyan-500 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-xs">
                                                {{ strtoupper(substr($user->fullname, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-bold text-slate-900 text-xs">{{ $user->fullname }}</div>
                                            <div class="text-[10px] text-slate-400">ID #{{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <code class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200/80 text-slate-800 font-mono text-[11px] font-semibold">{{ $user->username }}</code>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="text-slate-800 font-medium">{{ $user->email }}</div>
                                    @if($user->phone)
                                        <div class="text-[10px] text-slate-400 mt-0.5">{{ $user->phone }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $user->role === 'admin' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-indigo-50 text-indigo-700 border border-indigo-200' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $user->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-400 text-[11px]">
                                    {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white border border-indigo-100 transition-colors" title="Edit User">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </a>
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" data-confirm="Apakah Anda yakin ingin menghapus user {{ $user->fullname }}?" data-title="Hapus Pengguna" data-danger="true" data-confirm-text="Ya, Hapus!">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white border border-rose-100 transition-colors cursor-pointer" title="Hapus User">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                                        </div>
                                        <div class="font-bold text-slate-700 text-xs">Tidak ada user ditemukan</div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">Coba gunakan kata kunci pencarian atau filter yang lain.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-slate-500">
                <div>
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user
                </div>
                <div>
                    {{ $users->links() }}
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
    </div>
</x-app-layout>
