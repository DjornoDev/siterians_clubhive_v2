<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Temporary debug route
Route::get('/debug/export/test', function (Request $request) {
    $user = auth()->user();

    return response()->json([
        'authenticated' => auth()->check(),
        'user_role' => $user ? $user->role : 'not authenticated',
        'request_format' => $request->get('format'),
        'request_all' => $request->all(),
        'middleware_groups' => 'web',
        'controller_test' => class_exists('App\Http\Controllers\ExportController'),
    ]);
})->middleware(['web', 'auth']);
