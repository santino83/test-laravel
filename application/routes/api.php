<?php

use App\Http\Api\AuthController;
use App\Http\Api\BreweriesController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::delete('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/auth/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

Route::get('/breweries', [BreweriesController::class, 'listAll'])->middleware('auth:sanctum');
Route::get('/breweries/{id}', [BreweriesController::class, 'get'])->middleware('auth:sanctum');
