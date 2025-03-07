<?php

use App\Http\Controllers\StoreStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/store-status', [StoreStatusController::class, 'status']);
Route::get('/check-date', [StoreStatusController::class, 'checkDate']);
