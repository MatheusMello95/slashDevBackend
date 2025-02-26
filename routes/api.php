<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WidgetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes - no authentication required
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/ping', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Laravel API is connected!',
        'timestamp' => now()->toIso8601String()
    ]);
});

// Protected routes - authentication required
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // User management
    Route::apiResource('users', UserController::class);
    Route::get('users/{user}/widgets', [UserController::class, 'getWidgets']);

    // Widget routes
    Route::get('/widgets', [WidgetController::class, 'index']);
    Route::get('/user/widgets', [WidgetController::class, 'getUserWidgets']);
    Route::post('/user/widgets/{widget}', [WidgetController::class, 'addWidgetToUser']);
    Route::put('/user/widgets/{widget}', [WidgetController::class, 'updateUserWidget']);
    Route::delete('/user/widgets/{widget}', [WidgetController::class, 'removeUserWidget']);
    Route::post('/user/widgets/positions', [WidgetController::class, 'updateWidgetPositions']);
    Route::get('/widget-data/{widget}', [WidgetController::class, 'getWidgetData']);
});
