<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/me', function (Request $request) {
    return response()->json([
        'id' => $request->user()->id,
        'fullname' => $request->user()->fullname,
        'username' => $request->user()->username,
        'email' => $request->user()->email,
        'phone' => $request->user()->phone,
        'avatar' => $request->user()->avatar ? url(\Illuminate\Support\Facades\Storage::url($request->user()->avatar)) : null,
        'role' => $request->user()->role,
        'status' => $request->user()->status,
        'last_login' => $request->user()->last_login,
    ]);
});
