<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use App\Models\ImportDataCSV;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

#Rotas públicas
Route::post('login', [AuthController::class, 'login']);

#Rotas privadas
Route::middleware(['auth:sanctum'])->group(function () { 

    #Usuários
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'create']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'delete']);
    Route::post('users/delete', [UserController::class, 'bulkDelete']);

    #Vagas
    Route::get('jobs', [JobController::class, 'index']);
    Route::get('jobs/{id}', [JobController::class, 'show']);
    Route::post('jobs', [JobController::class, 'create']);
    Route::put('jobs/{id}', [JobController::class, 'update']);
    Route::delete('jobs/{id}', [JobController::class, 'delete']);
    Route::post('jobs/{id}/finish', [JobController::class, 'finishJob']);
    Route::post('jobs/{id}/pause', [JobController::class, 'pauseJob']);
    Route::post('jobs/delete', [JobController::class, 'bulkDelete']);

    #Candidaturas
    Route::get('applications/job/{id}', [ApplicationController::class, 'getApplicationsByJobID']);
    Route::get('applications/user/{id}', [ApplicationController::class, 'getApplicationsByUserID']);
    Route::post('applications', [ApplicationController::class, 'create']);
    Route::put('applications/{id}', [ApplicationController::class, 'updateStatus']);
    Route::delete('applications/{id}', [ApplicationController::class, 'delete']);
    Route::post('applications/delete', [ApplicationController::class, 'bulkDelete']);

    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('temperatures/information', function() {
    $data = ImportDataCSV::all();
    $finalData = [];

    foreach($data as $date){
        $finalData[Carbon::parse($date->date)->format('d/m/Y')] = [
            'Média' => round($date->average, 2),
            'Mediana' => round($date->median, 2),
            'Valor Mínimo' => $date->min_value,
            'Valor Máximo' => $date->max_value,
            'Porcentagem acima de 10' => round($date->percent_above_10, 2),
            'Porcentagem abaixo de -10' => round($date->percent_below_minus_10, 2),
            'Porcentagem entre -10 e 10' => round($date->percent_between_minus_10_and_10, 2),
        ];
    }

    return response()->json($finalData);
});

