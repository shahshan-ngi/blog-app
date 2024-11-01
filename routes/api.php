<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;

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


Route::middleware('withcookie')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user/{id}', [AuthController::class, 'getUser']);
    Route::get('/user/{userid}/blogs', [BlogController::class, 'myblogs']);
    Route::resource('blogs', BlogController::class);
});
Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);