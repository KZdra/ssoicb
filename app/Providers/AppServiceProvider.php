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
        \Laravel\Passport\Passport::authorizationView('auth.authorize');

        // Register Observers
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\ClientApplication::observe(\App\Observers\ClientApplicationObserver::class);

        // Passport token lifespans
        \Laravel\Passport\Passport::tokensExpireIn(now()->addDays(15));
        \Laravel\Passport\Passport::refreshTokensExpireIn(now()->addDays(30));
        \Laravel\Passport\Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        
        // Skip authorization prompt for seamless SSO experience
        // (Handled directly in the ClientApplication model now)


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
            $identity = $event->credentials['username'] ?? $event->credentials['email'] ?? 'unknown';
            \App\Services\AuditLogger::log($userId, 'Failed Login', 'Failed login attempt for identity: ' . $identity);
        });

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\PasswordReset::class, function ($event) {
            \App\Services\AuditLogger::log($event->user->id, 'Password Change', 'User reset their password.');
        });
    }
}
