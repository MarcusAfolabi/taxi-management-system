<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Controllers\CarController;
use App\Http\Controllers\UserController;


Route::post('v1/auth/register', [UserController::class, 'register']);
Route::post('v1/auth/login', [UserController::class, 'login']);

Route::middleware([JwtMiddleware::class])->group(
    function () {
        Route::resource('v1/cars', CarController::class);
    }
);
