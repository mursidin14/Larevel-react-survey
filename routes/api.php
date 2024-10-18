<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('users', [AuthController::class, 'register']);
Route::post('users/login', [AuthController::class, 'login']);

Route::middleware('api_auth')->group(function() {
    Route::get('/users', [AuthController::class, 'getUser']);
    Route::patch('/users', [AuthController::class, 'update']);
    Route::delete('/users', [AuthController::class, 'logout']);
});