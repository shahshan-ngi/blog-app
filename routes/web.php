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


// Route::get('/set-lang/{lang}', function ($lang){
//         app()->setLocale($lang);
//         session()->put('locale',$lang);
//         return redirect()->route('login');

   
// });
Route::get('/', function () {
    $locale = app()->getLocale() ?? config('app.locale'); 
    
    return redirect()->route('login', ['locale' => $locale]);
})->name('home');

Route::prefix('{locale}')->where(['locale' => '[a-zA-Z]{2}'])->middleware('localization')->group(function () {

    
    Route::get('/login', [AuthViewController::class, 'loginform'])->name('login');
    Route::get('/register', [AuthViewController::class, 'registerform'])->name('auth.registerform');


    Route::middleware(['auth'])->group(function () {
        
        Route::get('/myblogs', [BlogViewController::class, 'myblogs'])->name('blog.myblogs');

   
        Route::resource('blogs', BlogViewController::class)->except(['destroy', 'show']);

        Route::get('/blogs/{blog}/edit', [BlogViewController::class, 'edit'])->name('blog.updateview')->middleware('CheckOwner');
    });
});