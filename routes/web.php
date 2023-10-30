<?php

use App\Controllers\HomeController;
use App\Controllers\PostController;
use Pmguru\Framework\Http\Response;
use Pmguru\Framework\Routing\Route;

return [
	Route::get('/', [HomeController::class, 'index']),
	Route::get('/posts/{id:\d+}', [PostController::class, 'show']),
	Route::get('/posts/create', [PostController::class, 'create']),
];