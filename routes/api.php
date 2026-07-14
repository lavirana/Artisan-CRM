<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Fallback login route specifically to return tokens for API tools
Route::post('/login', [AuthController::class, 'apiLogin']);

// PROTECTED ROUTES: Only authenticated tokens can step through this gate
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('contacts', ContactController::class);
    // Add additional apiResources here for companies, deals, tasks, and dashboards!

    Route::apiResource('company', CompanyController::class);
});