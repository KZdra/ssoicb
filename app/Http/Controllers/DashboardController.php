<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClientApplication;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            // Metrics for Admin Dashboard
            $totalUsers = User::count();
            $activeUsers = User::where('status', 'active')->count();
            $inactiveUsers = User::where('status', 'inactive')->count();
            $adminUsers = User::where('role', 'admin')->count();
            $regularUsers = User::where('role', 'user')->count();

            $totalClients = ClientApplication::count();
            $activeClients = ClientApplication::where('status', 'active')->count();

            $activeSessionsCount = DB::table('sessions')->whereNotNull('user_id')->count();
            $totalSessionsCount = DB::table('sessions')->count();

            $totalLogs = AuditLog::count();
            $todayLogins = AuditLog::where('action', 'login')
                ->whereDate('created_at', Carbon::today())
                ->count();

            // Recent activity logs (with eager loaded users)
            $recentLogs = AuditLog::with('user')
                ->latest()
                ->take(7)
                ->get();

            // Recent registered users
            $recentUsers = User::latest()
                ->take(5)
                ->get();

            // Connected client apps
            $clientApps = ClientApplication::latest()
                ->take(5)
                ->get();

            // System environment metrics
            $systemInfo = [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_time' => Carbon::now()->format('d M Y H:i:s T'),
                'database_status' => 'Connected',
                'oauth_status' => 'Operational',
            ];

            return view('dashboard', compact(
                'totalUsers',
                'activeUsers',
                'inactiveUsers',
                'adminUsers',
                'regularUsers',
                'totalClients',
                'activeClients',
                'activeSessionsCount',
                'totalSessionsCount',
                'totalLogs',
                'todayLogins',
                'recentLogs',
                'recentUsers',
                'clientApps',
                'systemInfo'
            ));
        }

        // Regular User Dashboard Data
        $userSessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->get();

        $userLogs = AuditLog::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $availableClients = ClientApplication::where('status', 'active')->get();

        return view('dashboard', compact('userSessions', 'userLogs', 'availableClients'));
    }
}
