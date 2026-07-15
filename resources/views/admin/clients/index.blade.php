<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <span>{{ __('Client Applications Management') }}</span>
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary btn-sm">Add New Client</a>
        </div>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Client ID</th>
                            <th>Name</th>
                            <th>Redirect URL</th>
                            <th>Status</th>
                            <th>Secret</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td>{{ $client->id }}</td>
                                <td>
                                    {{ $client->name }}<br>
                                    <small class="text-muted">{{ Str::limit($client->description, 50) }}</small>
                                </td>
                                <td><code>{{ $client->redirect }}</code></td>
                                <td>
                                    <span class="badge {{ $client->status === 'active' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#secret-{{ $client->id }}" aria-expanded="false">
                                        Show Secret
                                    </button>
                                    <div class="collapse mt-2" id="secret-{{ $client->id }}">
                                        <code class="user-select-all">{{ $client->secret }}</code>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this client application?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No client applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $clients->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</x-app-layout>
