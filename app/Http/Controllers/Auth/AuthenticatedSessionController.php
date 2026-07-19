<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $clientId = $request->query('client_id') ?? old('client_id');
        $redirectUri = $request->query('redirect_uri');

        if ($redirectUri) {
            session(['url.intended' => $redirectUri]);
        }

        if (!$clientId && session()->has('url.intended')) {
            $intendedUrl = session()->get('url.intended');
            $parsedUrl = parse_url($intendedUrl);
            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $queryParams);
                $clientId = $queryParams['client_id'] ?? null;
            }
        }

        $clientApp = null;
        if ($clientId) {
            $clientApp = \App\Models\ClientApplication::where('id', $clientId)
                ->where('status', 'active')
                ->first();
        }

        return view('auth.login', compact('clientApp'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $clientId = $request->input('client_id');
        $clientName = 'SSO Dashboard';

        if ($clientId) {
            $clientApp = \App\Models\ClientApplication::find($clientId);
            if ($clientApp) {
                $clientName = $clientApp->name;
            }
        }

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'ip_address' => $request->ip(),
            'browser' => $request->header('User-Agent'),
            'operating_system' => null,
            'action' => 'Login',
            'description' => 'User logged in to ' . $clientName,
        ]);

        return redirect()->intended(route('dashboard', absolute: false))->with('success', 'Login berhasil!');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->broadcastLogout(Auth::guard('web')->user());
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Destroy an authenticated session via GET request (SSO Single Logout).
     */
    public function ssoLogout(Request $request): RedirectResponse
    {
        $this->broadcastLogout(Auth::guard('web')->user());
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $redirectUri = $request->query('redirect_uri');
        if ($redirectUri && (str_starts_with($redirectUri, 'http://127.0.0.1') || str_starts_with($redirectUri, 'http://192.168.0.9') || str_starts_with($redirectUri, 'http://localhost') || str_starts_with($redirectUri, 'https://'))) {
            return redirect($redirectUri);
        }

        return redirect('/login');
    }

    private function broadcastLogout($user)
    {
        if (!$user) return;
        
        $clients = \App\Models\ClientApplication::where('status', 'active')->get();
        foreach ($clients as $client) {
            $redirects = $client->redirect_uris; 
            if (is_string($redirects)) {
                $redirects = json_decode($redirects, true);
            }
            if (!is_array($redirects)) {
                $redirects = [$client->redirect_uris];
            }
            
            if (isset($redirects[0]) && is_string($redirects[0])) {
                $parsed = parse_url($redirects[0]);
                if (isset($parsed['scheme']) && isset($parsed['host'])) {
                    $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
                    $webhookUrl = $parsed['scheme'] . '://' . $parsed['host'] . $port . '/sso/slo';
                    
                    try {
                        \Illuminate\Support\Facades\Log::info('Broadcasting SLO to: ' . $webhookUrl . ' for username: ' . $user->username);
                        $response = \Illuminate\Support\Facades\Http::timeout(3)->post($webhookUrl, [
                            'username' => $user->username
                        ]);
                        \Illuminate\Support\Facades\Log::info('SLO Response from ' . $webhookUrl . ': ' . $response->status());
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('SLO Webhook Error (' . $webhookUrl . '): ' . $e->getMessage());
                    }
                }
            }
        }
    }
}
