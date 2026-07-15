<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Laravel\Passport\Passport::useClientModel(\App\Models\ClientApplication::class);

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            \App\Services\AuditLogger::log($event->user->id, 'Login', 'User logged in successfully.');
            $event->user->update(['last_login' => now()]);
        });

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Logout::class, function ($event) {
            if ($event->user) {
                \App\Services\AuditLogger::log($event->user->id, 'Logout', 'User logged out.');
            }
        });

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Failed::class, function ($event) {
            $user = $event->user;
            $userId = $user ? $user->id : null;
            \App\Services\AuditLogger::log($userId, 'Failed Login', 'Failed login attempt for email: ' . $event->credentials['email']);
        });

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\PasswordReset::class, function ($event) {
            \App\Services\AuditLogger::log($event->user->id, 'Password Change', 'User reset their password.');
        });
    }
}
