<x-app-layout>
    <x-slot name="header">
        {{ __('Active Sessions Management') }}
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
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
                                    <form action="{{ route('admin.sessions.destroy', $session->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to terminate this session? The user will be logged out.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Terminate</button>
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

            <div class="mt-3">
                {{ $sessions->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</x-app-layout>
