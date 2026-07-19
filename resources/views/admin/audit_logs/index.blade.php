<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <span class="mb-2 mb-md-0">{{ __('System Audit Logs') }}</span>
        </div>
    </x-slot>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="p-4 border-bottom d-flex flex-wrap justify-content-between align-items-center bg-white rounded-top gap-3">
                <h5 class="m-0 text-dark fw-semibold" style="font-size: 1rem;">All Logs</h5>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('admin.audit_logs.index') }}" method="GET" class="d-flex">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control" placeholder="Search logs..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-secondary bg-light text-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            </button>
                        </div>
                    </form>
                    
                    @if($logs->count() > 0 || request('search'))
                        <form action="{{ route('admin.audit_logs.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear ALL audit logs? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-1 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                <span class="d-none d-md-inline">Clear All Logs</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="text-nowrap">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    @if($log->user)
                                        {{ $log->user->fullname }} <br>
                                        <small class="text-muted">{{ $log->user->email }}</small>
                                    @else
                                        <span class="text-muted">System / Guest</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $log->action }}</span>
                                </td>
                                <td>{{ $log->description }}</td>
                                <td><code>{{ $log->ip_address }}</code></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No audit logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top">
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</x-app-layout>
