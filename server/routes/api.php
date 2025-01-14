<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login'])->name("login");
Route::post('register', [AuthController::class, 'register'])->name("register");
Route::post('todo-list/reorder', [TodoListController::class, 'reorder'])->name("todo-list.reorder");
Route::apiResource('todo-list', TodoListController::class)->middleware('auth:api');
