<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select('sessions.*', 'users.email', 'users.fullname')
            ->orderBy('last_activity', 'desc')
            ->paginate(15);
            
        return view('admin.sessions.index', compact('sessions'));
    }

    public function destroy($id)
    {
        DB::table('sessions')->where('id', $id)->delete();
        
        return redirect()->route('admin.sessions.index')->with('success', 'Session deleted successfully.');
    }
}
