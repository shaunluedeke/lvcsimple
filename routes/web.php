<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Storage;
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

#region Song
Route::resource('song', App\Http\Controllers\SongController::class);

Route::get('/song/{song}/updatethums/{action}', [App\Http\Controllers\SongController::class, 'updatethums'])->name('song.updatethums');
Route::get('/song/{song}/updatethums', [App\Http\Controllers\SongController::class, 'updatethums']);
Route::post('/song/{song}/addcommants', [App\Http\Controllers\SongController::class, 'addcommants'])->name('song.addcommants');


#endregion

#region Charts
Route::resource('charts', App\Http\Controllers\ChartController::class);
Route::get('/charts/{chart}/vote', [App\Http\Controllers\ChartController::class, 'vote']);
Route::post('/charts/{chart}/vote', [App\Http\Controllers\ChartController::class, 'vote'])->name('charts.vote');
#endregion

#region NewSong
Route::get('/new-song', static function () {
    return view('newsong.index');
})->middleware('auth')->name('newsong.index');

Route::post('/new-song', [App\Http\Controllers\MainController::class, 'newSong'])->middleware('auth');

#endregion

#region Statistik

Route::get('/statistik', function (){
    return view('statistik.index');
})->name('statistik.index');

#endregion

#region Storage

Route::prefix('storage')->group(static function(){

    Route::get('/song/new/{filename}', static function($filename){
        $path = storage_path('app/public/song/'). '/new/' . $filename;

        if(!File::exists($path)) { abort(404);}
        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    });

    Route::get('/song/{filename}', static function($filename){
        $path = storage_path('app/public/song/') . $filename;

        if(!File::exists($path)) { abort(404);}
        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    });
});

#endregion
