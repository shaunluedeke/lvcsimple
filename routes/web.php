<?php

use App\Models\Chart;
use App\Models\SongLog;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Resouces\AdminController;

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

#region Home
Route::get('/', static function () {
    $charts = Chart::all();
    $activeCharts = []; $charts->where('is_active', true)->where('autoset',1);
    $inactiveCharts = [];
    foreach ($charts as $chart){
        if($chart->is_active && $chart->autoset === 1 && !$chart->userhasVoted()){
            $activeCharts[] = $chart;
        }else{
            $inactiveCharts[] = $chart;
        }
    }

    return view('index', ['active' => $activeCharts, 'inactive' => $inactiveCharts, 'ad' => \App\Http\Controllers\AdController::getRandomAd()]);
})->name('home');

Route::get('login', static function () {
    return view('auth.login');
})->name('login');

Route::post('login', [ \App\Http\Controllers\Resouces\LoginController::class, 'login' ]);
Route::post('logout', [ \App\Http\Controllers\Resouces\LoginController::class, 'logout' ])->name('logout');


Route::redirect('/register', 'https://lvcharts.de/wls/index.php?register/')->name('register');

Route::redirect("/home", "/");


#endregion

#region Song
Route::resource('song', \App\Http\Controllers\Resouces\SongController::class);

Route::get('/song/{song}/updatethums/{action}', [\App\Http\Controllers\Resouces\SongController::class, 'updatethums'])->name('song.updatethums');
Route::get('/song/{song}/updatethums', [\App\Http\Controllers\Resouces\SongController::class, 'updatethums']);
Route::post('/song/{song}/addcommants', [\App\Http\Controllers\Resouces\SongController::class, 'addcommants'])->name('song.addcommants');


#endregion

#region Charts
Route::resource('charts', \App\Http\Controllers\Resouces\ChartController::class);
Route::get('/charts/{chart}/vote', [\App\Http\Controllers\Resouces\ChartController::class, 'vote']);
Route::post('/charts/{chart}/vote', [\App\Http\Controllers\Resouces\ChartController::class, 'vote'])->name('charts.vote');
#endregion

#region NewSong
Route::get('/new-song', static function () {
    return view('newsong.index');
})->middleware('auth')->name('newsong.index');

Route::post('/new-song', [App\Http\Controllers\MainController::class, 'newSong'])->middleware('auth')->name('newsong.create');

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

#region SongLogs
Route::get('songlogs', static function () {
    return view('songlog.index', ['logs'=> SongLog::all()]);
})->name('songlog.index');
#endregion

#region Admin
Route::prefix('/admin')->middleware('auth')->group(static function() {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    Route::prefix('/charts')->group(function () {
        Route::get('/', [AdminController::class, 'charts'])->name('admin.charts');
        Route::get('/create', [AdminController::class, 'chartscreate'])->name('admin.charts.create');

        Route::post('/create', [AdminController::class, 'chartsstore'])->name('admin.charts.store');

        Route::prefix('/{chart}')->group(function () {
            Route::get('/', [AdminController::class, 'charts'])->name('admin.charts.id');
            Route::get('/points', [AdminController::class, 'chartspoints'])->name('admin.charts.id.points');
            Route::post('/points', [AdminController::class, 'chartsvote'])->name('admin.charts.id.points.execute');
            Route::get('/active/{action}', [AdminController::class, 'chartsactive'])->name('admin.charts.id.active');
        });
    });

    Route::prefix('/songs')->group(function () {
        Route::get('/', [AdminController::class, 'songs'])->name('admin.songs');
        Route::get('/edit/{song}', [AdminController::class, 'songedit'])->name('admin.songs.edit');
        Route::delete('/delete/{song}', [AdminController::class, 'songdelete'])->name('admin.songs.delete');
        Route::put('/edit/{song}', [AdminController::class, 'songeditsave'])->name('admin.songs.edit.save');
    });

    Route::prefix('/newsong')->group(function () {
        Route::get('/', [AdminController::class, 'newsong'])->name('admin.newsong');
        Route::get('/accept/{song}', [AdminController::class, 'newsongaccept'])->name('admin.newsong.accept');
        Route::get('/delete/{song}', [AdminController::class, 'newsongdelete'])->name('admin.newsong.delete');
    });

    Route::prefix('/bcd')->group(function () {
        Route::get('/', [AdminController::class, 'bcd'])->name('admin.bcd');
        Route::get('/create', [AdminController::class, 'bcdcreate'])->name('admin.bcd.create');
        Route::post('/create', [AdminController::class, 'bcdstore'])->name('admin.bcd.store');
        Route::delete('/delete/{bcd}', [AdminController::class, 'bcddelete'])->name('admin.bcd.delete');
    });
});


#endregion

