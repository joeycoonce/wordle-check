<?php
use App\Http\Controllers\WordleController;

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

Route::get('/', function () {
    return view('wordle');
});

Route::post('/', \App\Http\Controllers\WordleController::class)->name('wordle.guess');

Route::get('/csrf-token', \App\Http\Controllers\RefreshCsrfTokenController::class)->name('csrf-token');

Route::get('refresh-csrf', function(){
    return csrf_token();
})->name('refresh-csrf');