<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthViewController;
use App\Http\Controllers\BlogViewController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login',[AuthViewController::class,'loginform'])->name('login');
Route::get('/register',[AuthViewController::class,'registerform'])->name('auth.registerform');
Route::get('/myblogs',[BlogViewController::class,'myblogs']);
Route::resource('blogs', BlogViewController::class)->except(['destroy','show']);