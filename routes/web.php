<?php

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SingleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('home' , [HomeController::class , 'index'])->name('home');
Route::get('single/{id}' , [SingleController::class , 'index'])->name('single');
Route::post('single/{post}/comment' , [SingleController::class , 'commentStore'])->name('single.comment')
    ->middleware('auth');
Route::prefix('admin')->middleware(['admin'])->name('admin.')->group(function (){
Route::resource('posts' , PostController::class);
Route::resource('tags' , TagController::class);
});

Auth::routes();

