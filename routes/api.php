<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\PlacementTestController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\WeeklyChallengeController;
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

    Route::get('/lesson-stats', [LessonController::class, 'stats']);
    Route::get('/lessons/progress', [LessonController::class, 'progressSummary']);
    Route::get('/lessons', [LessonController::class, 'index']);
    Route::get('/lessons/{lesson}', [LessonController::class, 'show']);
    Route::post('/lessons/{lesson}/start', [LessonController::class, 'start']);
    Route::post('/lessons/{lesson}/complete', [LessonController::class, 'complete']);

    Route::post('/exercises/{exercise}/submit', [ExerciseController::class, 'submit']);

    Route::get('/placement-test', [PlacementTestController::class, 'index']);
    Route::post('/placement-test/submit', [PlacementTestController::class, 'submit']);
    Route::post('/placement-test/apply', [PlacementTestController::class, 'apply']);

    Route::get('/badges', [BadgeController::class, 'index']);

    Route::get('/weekly-challenge', [WeeklyChallengeController::class, 'current']);
});