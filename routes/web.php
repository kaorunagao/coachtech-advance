<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimestampController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


// 打刻ページ
Route::get('/', function () {
    $user = Auth::user();
    return view("timestamp",["user"=>$user]);
})->middleware(["auth"]);

Route::post('/', function () {
    [TimestampController::class,"create"];
})->middleware(["auth"]);

