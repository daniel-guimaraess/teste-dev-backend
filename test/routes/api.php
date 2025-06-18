<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

#Rotas públicas
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () { 

    #Usuários
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'create']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'delete']);

    #Vagas
    Route::get('jobs', [JobController::class, 'index']);
    Route::get('jobs/{id}', [JobController::class, 'show']);
    Route::post('jobs', [JobController::class, 'create']);
    Route::put('jobs/{id}', [JobController::class, 'update']);
    Route::delete('jobs/{id}', [JobController::class, 'delete']);
    Route::post('jobs/{id}/finish', [JobController::class, 'finishJob']);
    Route::post('jobs/{id}/pause', [JobController::class, 'pauseJob']);

    #Candidaturas
    Route::get('applications/job/{id}', [ApplicationController::class, 'getApplicationsByJobID']);
    Route::post('applications', [ApplicationController::class, 'create']);
    Route::put('applications/{id}', [ApplicationController::class, 'updateStatus']);
    Route::delete('applications/{id}', [ApplicationController::class, 'delete']);
});

