<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ResumeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    
    // Authentication routes with strict rate limiting
    Route::middleware('throttle:auth')->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);
        Route::post('/auth/register', [AuthController::class, 'register']);
    });

    // Protected routes with general API rate limiting
    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
        
        // Profile routes
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        
        // Job browsing
        Route::get('/jobs', [JobController::class, 'index']);
        Route::get('/jobs/{id}', [JobController::class, 'show']);
        
        // Job application
        Route::post('/jobs/{id}/apply', [ApplicationController::class, 'apply']);
        
        // Applications
        Route::get('/applications', [ApplicationController::class, 'index']);
        Route::get('/applications/{id}', [ApplicationController::class, 'show']);
        
        // Resume management
        Route::get('/resumes', [ResumeController::class, 'index']);
        Route::post('/resumes', [ResumeController::class, 'store']);
        Route::get('/resumes/{id}', [ResumeController::class, 'show']);
        Route::put('/resumes/{id}', [ResumeController::class, 'update']);
        Route::delete('/resumes/{id}', [ResumeController::class, 'destroy']);
        
        // Logout
        Route::post('/auth/logout', [AuthController::class, 'logout']);
    });
});
