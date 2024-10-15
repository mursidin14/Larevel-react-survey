<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('users', [AuthController::class, 'register']);
Route::post('users/login', [AuthController::class, 'login']);