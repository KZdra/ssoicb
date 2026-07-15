<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <span class="mb-2 mb-md-0">{{ __('Client Applications') }}</span>
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add New Client
            </a>
        </div>
    </x-slot>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white rounded-top">
                <h5 class="m-0 text-dark fw-semibold" style="font-size: 1rem;">All Clients</h5>
                <form action="{{ route('admin.clients.index') }}" method="GET" class="d-flex">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" name="search" class="form-control" placeholder="Search clients..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-secondary bg-light text-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>client_id</th>
                            <th>application_name</th>
                            <th>redirect_uri</th>
                            <th>status</th>
                            <th>client_secret</th>
                            <th>description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td>{{ $client->id }}</td>
                                <td>{{ $client->name }}</td>
                                <td><code>{{ is_array($client->redirect_uris) ? implode(', ', $client->redirect_uris) : ($client->redirect ?? 'N/A') }}</code></td>
                                <td>
                                    <span class="badge {{ $client->status === 'active' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($client->secret)
                                        <div class="d-flex flex-column gap-2 align-items-start">
                                            <button class="btn btn-sm btn-light border shadow-sm text-secondary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#secret-{{ $client->id }}" aria-expanded="false">
                                                Show Secret
                                            </button>
                                            <div class="collapse w-100" id="secret-{{ $client->id }}">
                                                <code class="user-select-all d-block bg-light p-2 border rounded">{{ $client->secret }}</code>
                                            </div>
                                            <form action="{{ route('admin.clients.regenerate-secret', $client->id) }}" method="POST" class="w-100" onsubmit="return confirm('Regenerate secret? Old secret will no longer work.');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light border shadow-sm text-warning w-100">Regenerate</button>
                                            </form>
                                        </div>
                                    @else
                                        <form action="{{ route('admin.clients.generate-secret', $client->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success shadow-sm">Generate Secret</button>
                                        </form>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($client->description, 50) }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-sm btn-light text-primary border shadow-sm" title="Edit Client">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this client application?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger border shadow-sm" title="Delete Client">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No client applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top">
                {{ $clients->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</x-app-layout>
