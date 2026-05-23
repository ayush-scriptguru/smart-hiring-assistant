<?php

use App\Enums\UserRole;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\RecruiterProfileController;
use App\Http\Controllers\ProfileImageController;
use App\Http\Controllers\ResumeScannerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/profile/image', [ProfileImageController::class, 'update'])->name('profile.image.update');

    Route::middleware('role:'.UserRole::Candidate->value)->group(function (): void {
        Route::patch('/candidate/profile', [CandidateProfileController::class, 'update'])
            ->name('candidate.profile.update');
        Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])
            ->name('applications.store');
    });

    Route::middleware('role:'.UserRole::Admin->value.','.UserRole::Recruiter->value)->group(function (): void {
        Route::patch('/recruiter/profile', [RecruiterProfileController::class, 'update'])
            ->name('recruiter.profile.update');
        Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
        Route::patch('/jobs/{job}/status', [JobController::class, 'updateStatus'])->name('jobs.status.update');
        Route::patch('/applications/{application}', [ApplicationController::class, 'update'])->name('applications.update');
        Route::post('/applications/{application}/refresh', [ApplicationController::class, 'refresh'])->name('applications.refresh');
        Route::post('/applications/{application}/automate', [ApplicationController::class, 'automate'])->name('applications.automate');
        Route::post('/applications/{application}/interviews', [InterviewController::class, 'store'])->name('interviews.store');
        Route::patch('/interviews/{interview}', [InterviewController::class, 'update'])->name('interviews.update');
        Route::post('/resume/scan', [ResumeScannerController::class, 'scan'])->name('resume.scan');
    });
});
