<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StatsController;


Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/verify-code', [AuthController::class, 'verifyCode']);



Route::apiResource('tags', TagController::class)->middleware('auth:sanctum');

Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');

Route::get('trashed', [PostController::class, 'trashed'])->middleware('auth:sanctum');
Route::post('/restore/{id}', [PostController::class, 'restore'])->middleware('auth:sanctum');

Route::get('stats', [StatsController::class, 'index'])->middleware('auth:sanctum');