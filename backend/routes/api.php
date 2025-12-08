<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AssessmentController;

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

// Contact API endpoints
Route::post('/contact', [ContactController::class, 'store']);

// Assessment API endpoints
Route::post('/assessment', [AssessmentController::class, 'store']);
Route::get('/assessment/{id}', [AssessmentController::class, 'show']);
Route::get('/assessment/{id}/export/pdf', [AssessmentController::class, 'exportPdf']);
Route::get('/assessment/{id}/export/excel', [AssessmentController::class, 'exportExcel']);
