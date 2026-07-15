<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="row">
        @if(auth()->user()->role === 'admin')
            <!-- Admin Stats -->
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Total Users</div>
                                <div class="text-lg fw-bold">{{ \App\Models\User::count() }}</div>
                            </div>
                            <i class="fas fa-users fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Client Apps</div>
                                <div class="text-lg fw-bold">{{ \App\Models\ClientApplication::count() }}</div>
                            </div>
                            <i class="fas fa-server fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-white shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Active Sessions</div>
                                <div class="text-lg fw-bold">{{ \Illuminate\Support\Facades\DB::table('sessions')->count() }}</div>
                            </div>
                            <i class="fas fa-key fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-danger text-white shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Audit Logs</div>
                                <div class="text-lg fw-bold">{{ \App\Models\AuditLog::count() }}</div>
                            </div>
                            <i class="fas fa-history fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- User Stats -->
            <div class="col-md-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title">Welcome, {{ auth()->user()->fullname }}!</h5>
                        <p class="card-text text-muted">You are successfully logged in to the Central Authentication Server.</p>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">Update Profile</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
