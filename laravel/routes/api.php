<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

Route::prefix('v1')->group(function () {

    // Health check
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
            'redis' => Cache::store('redis')->get('test') !== false ? 'connected' : 'disconnected',
            'timestamp' => now()->toIso8601String(),
        ]);
    });

    // Public routes
    Route::prefix('auth')->group(function () {
        Route::post('/register', function () {
            return response()->json(['message' => 'Registration endpoint - coming soon']);
        });

        Route::post('/login', function () {
            return response()->json(['message' => 'Login endpoint - coming soon']);
        });
    });

    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // Planets API (coming soon)
        Route::prefix('planets')->group(function () {
            Route::get('/', function () {
                return response()->json(['message' => 'Planets list - coming soon']);
            });
        });

        // Fleets API (coming soon)
        Route::prefix('fleets')->group(function () {
            Route::get('/', function () {
                return response()->json(['message' => 'Fleets list - coming soon']);
            });
        });

        // Research API (coming soon)
        Route::prefix('research')->group(function () {
            Route::get('/', function () {
                return response()->json(['message' => 'Research list - coming soon']);
            });
        });

    });

});
