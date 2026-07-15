<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="h4 fw-bold">Otorisasi Aplikasi</h2>
        <p class="text-muted small"><strong>{{ $client->name }}</strong> meminta izin untuk mengakses akun Anda.</p>
    </div>

    <!-- Scopes List -->
    @if (count($scopes) > 0)
        <div class="mb-4">
            <h5 class="small fw-semibold text-uppercase text-muted mb-2" style="font-size: 0.75rem;">Izin yang Diminta:</h5>
            <ul class="list-group list-group-flush border rounded-3 overflow-hidden" style="font-size: 0.85rem;">
                @foreach ($scopes as $scope)
                    <li class="list-group-item bg-light py-2 d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        {{ $scope->description }}
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="alert alert-light border text-center small text-muted mb-4 py-2">
            Aplikasi ini tidak meminta izin akses data tambahan.
        </div>
    @endif

    <div class="d-flex flex-column gap-2 mt-4">
        <!-- Approve Form -->
        <form method="POST" action="{{ route('passport.authorizations.approve') }}">
            @csrf
            <input type="hidden" name="state" value="{{ $request->state }}">
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">
            <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold shadow-sm">
                Izinkan & Lanjutkan
            </button>
        </form>

        <!-- Deny Form -->
        <form method="POST" action="{{ route('passport.authorizations.deny') }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="state" value="{{ $request->state }}">
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">
            <button type="submit" class="btn btn-light border text-danger w-100 fw-semibold">
                Tolak Akses
            </button>
        </form>
    </div>
</x-guest-layout>
