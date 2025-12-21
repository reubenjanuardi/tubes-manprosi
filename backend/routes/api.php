<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\IndicatorController;

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

// Indicator endpoints (PUBLIC - For frontend to fetch indicators)
Route::get('/indicators', [IndicatorController::class, 'getIndicators']);
Route::get('/indicators/version', [IndicatorController::class, 'getVersion']);

// Assessment endpoints (PUBLIC - No login required)
Route::post('/assessment', [AssessmentController::class, 'store']);
Route::get('/assessment/{id}', [AssessmentController::class, 'show']);
Route::get('/assessment/{id}/export/pdf', [AssessmentController::class, 'exportPdf']);
Route::get('/assessment/{id}/export/spbe-pdf', [AssessmentController::class, 'exportSpbePdf']);

// Progress endpoints (PUBLIC)
Route::get('/assessment/progress', [ProgressController::class, 'index']);
Route::post('/assessment/progress', [ProgressController::class, 'store']);
Route::get('/assessment/progress/{id}', [ProgressController::class, 'show']);
Route::delete('/assessment/progress/{id}', [ProgressController::class, 'destroy']);

// ============================================================================
// AUTH ROUTES (Optional - Not required for assessment)
// ============================================================================

// Authentication endpoints (Sanctum - existing)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ============================================================================
// JWT AUTH ROUTES (Phase 2)
// ============================================================================

Route::prefix('auth')->group(function () {
    Route::post('/jwt/login', [JWTAuthController::class, 'login']);
    Route::post('/jwt/register', [JWTAuthController::class, 'register']);
    
    Route::middleware('auth:api')->group(function () {
        Route::get('/me', [JWTAuthController::class, 'me']);
        Route::post('/logout', [JWTAuthController::class, 'logout']);
        Route::post('/refresh', [JWTAuthController::class, 'refresh']);
        Route::put('/profile', [JWTAuthController::class, 'updateProfile']);
        Route::post('/change-password', [JWTAuthController::class, 'changePassword']);
    });
});

// ============================================================================
// LEGACY SANCTUM ROUTES (Keep for backward compatibility)
// ============================================================================

Route::middleware('auth:sanctum')->group(function () {
    // Auth endpoints
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
