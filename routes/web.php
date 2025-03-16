<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\IncomesController;
use App\Http\Middleware\ApiKeyMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(ApiKeyMiddleware::class)->group(function () {
    Route::get('/sales', [SalesController::class, 'index']);
    Route::get('/orders', [OrdersController::class, 'index']);
    Route::get('/stocks', [StocksController::class, 'index']);
    Route::get('/incomes', [IncomesController::class, 'index']);
});

