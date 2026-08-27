<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

Route::get('/', function () {
    return view('landing');
});

// Form Submissions (Session Based)
Route::post('/assessment/submit', [LandingController::class, 'submitAssessment'])->name('assessment.submit');
Route::post('/contact/submit', [LandingController::class, 'submitContact'])->name('contact.submit');
