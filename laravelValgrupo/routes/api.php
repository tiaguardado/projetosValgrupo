<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::post('/logout',[AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::get('/users', [UserController::class, 'index']);    // Listar todos
Route::get('/users/{id}', [UserController::class, 'show']); // Mostrar 1
Route::put('/users/{id}', [UserController::class, 'update']); // Atualizar
Route::delete('/users/{id}', [UserController::class, 'destroy']); // Soft delete
