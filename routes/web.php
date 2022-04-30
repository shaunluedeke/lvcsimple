<?php

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

Route::get('/', static function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('song', App\Http\Controllers\SongController::class);

Route::get('/song/{song}/updatethums/{action}', [App\Http\Controllers\SongController::class, 'updatethums'])->name('song.updatethums');
Route::get('/song/{song}/updatethums', [App\Http\Controllers\SongController::class, 'updatethums']);

Route::post('/song/{song}/addcommants', [App\Http\Controllers\SongController::class, 'addcommants'])->name('song.addcommants');
