<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'view']);
Route::get('/{type}/{group?}/{undergroup?}', [HomeController::class, 'view'])->name('view');

