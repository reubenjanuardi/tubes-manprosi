<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AssessmentController;

// Landing Page
Route::get('/', function () {
    return view('landing');
})->name('home');

// Contact Form Submission
Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');

// Assessment Routes (direct to controller, no API)
Route::post('/assessment/submit', [AssessmentController::class, 'store'])->name('assessment.submit');
Route::get('/assessment/{id}', [AssessmentController::class, 'show'])->name('assessment.show');
Route::get('/assessment/{id}/export-pdf', [AssessmentController::class, 'exportSpbePdf'])->name('assessment.export');

