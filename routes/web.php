<?php

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SingleController;
use Illuminate\Support\Facades\Route;


Route::get('home' , [HomeController::class , 'index'])->name('home');
Route::get('single/{post}' , [SingleController::class , 'index'])->name('single');

Route::post('single/{post}/comment' , [SingleController::class , 'commentStore'])
    ->name('single.comment')
    ->middleware('auth');

Route::prefix('admin')->middleware(['auth' , 'admin' ])->name('admin.')->group(function (){

Route::resource('posts' , PostController::class)->except('show');
Route::resource('tags' , TagController::class)->except('show');
Route::resource('users' , UserController::class)->only('show');

});

Auth::routes();

