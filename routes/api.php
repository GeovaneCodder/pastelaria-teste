<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\{
    ClientController,
    ProductController,
    OrderController,
};

Route::get('/', function() {
    return response()
        ->json(['Comerc API']);
});

Route::prefix('/v1')->group(function() {
    Route::apiResource('client', ClientController::class);
    Route::apiResource('product', ProductController::class);
    Route::apiResource('order', OrderController::class);
});
