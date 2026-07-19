<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Are You Lost? Honey? <:',
    ]);
});

Route::get('/sso/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'ssoLogout'])->name('sso.logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        
        Route::post('clients/{id}/generate-secret', [\App\Http\Controllers\Admin\ClientController::class, 'generateSecret'])->name('clients.generate-secret');
        Route::post('clients/{id}/regenerate-secret', [\App\Http\Controllers\Admin\ClientController::class, 'regenerateSecret'])->name('clients.regenerate-secret');
        Route::resource('clients', \App\Http\Controllers\Admin\ClientController::class);
        
        Route::get('sessions', [\App\Http\Controllers\Admin\SessionController::class, 'index'])->name('sessions.index');
        Route::delete('sessions/{id}', [\App\Http\Controllers\Admin\SessionController::class, 'destroy'])->name('sessions.destroy');

        Route::get('audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit_logs.index');
        Route::delete('audit-logs/clear', [\App\Http\Controllers\Admin\AuditLogController::class, 'clear'])->name('audit_logs.clear');
    });
});

require __DIR__.'/auth.php';
