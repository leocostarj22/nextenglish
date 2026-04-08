<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\ScenarioController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/scenarios', [ScenarioController::class, 'index']);

    Route::post('/practice/evaluate', [PracticeController::class, 'evaluate']);
    Route::post('/practice/questions', [PracticeController::class, 'questions']);

    Route::get('/history', [HistoryController::class, 'index']);
    Route::delete('/history', [HistoryController::class, 'clear']);
    Route::get('/history/{turn}', [HistoryController::class, 'show']);
    Route::delete('/history/{turn}', [HistoryController::class, 'destroy']);
});