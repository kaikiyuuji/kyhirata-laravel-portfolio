<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
| These routes are loaded by the bootstrap/app.php file and are
| assigned the "api" middleware group and "api/v1/admin" prefix.
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });
});
