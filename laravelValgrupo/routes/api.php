<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EncomendaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::post('/logout',[AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::get('/users', [UserController::class, 'index'])->middleware('auth:sanctum');    // Listar todos
Route::get('/users/{id}', [UserController::class, 'show'])->middleware('auth:sanctum'); // Mostrar 1
Route::put('/users/{id}', [UserController::class, 'update'])->middleware('auth:sanctum'); // Atualizar
Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('auth:sanctum'); // Soft delete

Route::apiResource('encomendas', EncomendaController::class)->middleware('auth:sanctum');
