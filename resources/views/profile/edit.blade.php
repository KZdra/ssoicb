<x-app-layout>
    <x-slot name="header">
        {{ __('Profile') }}
    </x-slot>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 p-md-5">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 p-md-5">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card shadow-sm border-danger">
                <div class="card-body p-4 p-md-5">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
