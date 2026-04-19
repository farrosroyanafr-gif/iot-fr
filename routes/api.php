<?php

use App\Http\Controllers\SensorController;
use Illuminate\Support\Facades\Route;

Route::post('/data', [SensorController::class, 'store']);
Route::get('/data/latest', [SensorController::class, 'latest']);
Route::get('/data/history', [SensorController::class, 'history']);
Route::get('/pump/status', [SensorController::class, 'pumpStatus']);
Route::post('/pump/toggle', [SensorController::class, 'togglePump']);