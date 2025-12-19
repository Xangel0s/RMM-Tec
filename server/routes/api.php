<?php

use App\Http\Controllers\Api\CommandController;
use App\Http\Controllers\Api\HeartbeatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/heartbeat', [HeartbeatController::class, 'store']);

// External Sticker Portal Routes
Route::prefix('portal')->group(function () {
    Route::post('/tickets', [\App\Http\Controllers\Api\ExternalTicketController::class, 'store']);
    Route::get('/tickets/{public_id}', [\App\Http\Controllers\Api\ExternalTicketController::class, 'track']);
});

Route::get('/commands/pending', [CommandController::class, 'pending']);
Route::post('/commands/{id}/result', [CommandController::class, 'result']);
