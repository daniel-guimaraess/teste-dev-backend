<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

#Rotas públicas
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () { 


});

