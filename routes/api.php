<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CouncilController;
use App\Http\Controllers\ExpeditionController;
use App\Http\Controllers\ExpeditionStatusController;
use App\Http\Controllers\KingdomController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- Rotas Públicas ---
Route::post('/auth', [AuthController::class, 'authenticate']);
Route::post('/user', [UserController::class, 'store']);

Route::controller(RoleController::class)->prefix('role')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
});


// --- Rotas Protegidas ---
Route::middleware('auth:sanctum')->group(function () {

    // --- Rotas de usuário logado ---
    Route::controller(UserController::class)->prefix('auth')->group(function () {
        Route::get('/user', 'get');
    });

    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
    });

    Route::controller(CouncilController::class)->prefix('council')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
    });

    Route::controller(KingdomController::class)->prefix('kingdom')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
    });

    Route::controller(ExpeditionController::class)->prefix('expedition')->group(function () {
        Route::get('/{protocolId}', 'get');
        Route::post('/', 'store');
        Route::put('/{protocolId}', 'update');
    });

    Route::controller(ExpeditionStatusController::class)->prefix('expedition-status')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
    });
});
