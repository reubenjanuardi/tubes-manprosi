<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AdminController;

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

// Admin Routes
Route::get('/admin', [AdminController::class, 'showLogin'])->name('login'); // Laravel auth expects 'login' route
Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login'); // Alternative URL
Route::post('/admin/login', [AdminController::class, 'authenticate'])->name('admin.login.submit');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/assessments', [AdminController::class, 'assessments'])->name('admin.assessments');
    Route::get('/admin/domains', [AdminController::class, 'domains'])->name('admin.domains');
    Route::get('/admin/indicators', [AdminController::class, 'indicators'])->name('admin.indicators');
    Route::get('/admin/subdomains', [AdminController::class, 'subdomains'])->name('admin.subdomains');
    Route::get('/admin/structure', [AdminController::class, 'structure'])->name('admin.structure');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
});

