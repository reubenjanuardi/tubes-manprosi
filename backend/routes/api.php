<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ProgressController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// ============================================================================
// PUBLIC ROUTES (No authentication required)
// ============================================================================

// Contact form endpoint (public)
Route::post('/contact', [ContactController::class, 'store']);

// Assessment endpoints (PUBLIC - No login required)
Route::post('/assessment', [AssessmentController::class, 'store']);
Route::get('/assessment/{id}', [AssessmentController::class, 'show']);
Route::get('/assessment/{id}/export/pdf', [AssessmentController::class, 'exportPdf']);
Route::get('/assessment/{id}/export/excel', [AssessmentController::class, 'exportExcel']);
Route::get('/indicators', [AssessmentController::class, 'getIndicators']);

// Progress endpoints (PUBLIC)
Route::get('/assessment/progress', [ProgressController::class, 'index']);
Route::post('/assessment/progress', [ProgressController::class, 'store']);
Route::get('/assessment/progress/{id}', [ProgressController::class, 'show']);
Route::delete('/assessment/progress/{id}', [ProgressController::class, 'destroy']);

// ============================================================================
// AUTH ROUTES (Optional - Not required for assessment)
// ============================================================================

// Authentication endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth endpoints
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
});
