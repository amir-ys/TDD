<?php

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
Route::post('single/{id}/comment' , [SingleController::class , 'commentStore'])->name('single.comment');

Auth::routes();

