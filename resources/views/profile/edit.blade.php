<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Pengaturan Akun & Profil') }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola informasi pribadi, ubah password akun, atau amankan sesi login Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Profile Info Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Password Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
            @include('profile.partials.update-password-form')
        </div>

        <!-- Danger Zone Card -->
        <div class="bg-white rounded-2xl border border-rose-200 shadow-xs p-6 sm:p-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 bottom-0 w-1.5 bg-rose-500"></div>
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
