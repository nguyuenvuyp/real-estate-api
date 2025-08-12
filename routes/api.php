<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyImageController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::apiResource('properties', PropertyController::class);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('properties', PropertyController::class);
    Route::post('/properties/{id}/restore', [PropertyController::class, 'restore']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/properties/{propertyId}/images', [PropertyImageController::class, 'store']);
    Route::delete('/images/{id}', [PropertyImageController::class, 'destroy']);
    Route::patch('/images/{id}/primary', [PropertyImageController::class, 'setPrimary']);
    Route::patch('/images/{id}/sort', [PropertyImageController::class, 'updateSort']);
});
