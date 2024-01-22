<?php

use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\PostController;
use App\Controllers\RegisterController;
use Pmguru\Framework\Http\Middleware\Authenticate;
use Pmguru\Framework\Http\Middleware\Guest;
use Pmguru\Framework\Routing\Route;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/dashboard', [DashboardController::class, 'index'], [Authenticate::class]),
    
    Route::get('/posts/{id:\d+}', [PostController::class, 'show']),
    Route::get('/posts/create', [PostController::class, 'create']),
    Route::post('/posts', [PostController::class, 'store']),
    
    Route::get('/register', [RegisterController::class, 'form'], [Guest::class]),
    Route::post('/register', [RegisterController::class, 'register']),
    
    Route::get('/login', [LoginController::class, 'form'], [Guest::class]),
    Route::post('/login', [LoginController::class, 'login']),
    Route::post('/logout', [LoginController::class, 'logout']),
];