<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <span class="mb-2 mb-md-0">{{ __('Active Sessions') }}</span>
        </div>
    </x-slot>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="p-4 border-bottom bg-white rounded-top">
                <h5 class="m-0 text-dark fw-semibold" style="font-size: 1rem;">Current Active Sessions</h5>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                            <th>Last Activity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                            <tr>
                                <td>
                                    @if($session->user_id)
                                        <strong>{{ $session->fullname }}</strong><br>
                                        <small class="text-muted">{{ $session->email }}</small>
                                    @else
                                        <span class="text-muted">Guest</span>
                                    @endif
                                </td>
                                <td><code>{{ $session->ip_address }}</code></td>
                                <td>
                                    <small class="d-inline-block text-truncate" style="max-width: 250px;" title="{{ $session->user_agent }}">
                                        {{ $session->user_agent }}
                                    </small>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                </td>
                                <td>
                                    <form action="{{ route('admin.sessions.destroy', $session->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to terminate this session? The user will be logged out.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border shadow-sm d-flex align-items-center gap-1" title="Terminate Session">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                                            <span class="d-none d-md-inline">Terminate</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No active sessions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top">
                {{ $sessions->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</x-app-layout>
