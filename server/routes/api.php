<?php

use App\Http\Controllers\Api\CommandController;
use App\Http\Controllers\Api\HeartbeatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/heartbeat', [HeartbeatController::class, 'store']);

Route::get('/commands/pending', [CommandController::class, 'pending']);
Route::post('/commands/{id}/result', [CommandController::class, 'result']);
