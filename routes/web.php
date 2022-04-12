<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimestampController;
use App\Http\Controllers\RestController;
use App\Http\Controllers\AttendanceController;

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


Route::get('/', function () {
    $user = Auth::user();
    return view("timestamp",["user"=>$user]);
})->middleware(["auth"]);
// 打刻、表示、処理
Route::post('/', function () {
    [TimestampController::class,"showTimestamp"];
})->middleware(["auth"]);


Route::group(['middleware' => 'auth'], function () {
//勤怠開始
Route::post("/time_start",[TimestampController::class,"timeStart"]);
// 勤怠終了
Route::post("/time_end", [TimestampController::class,"timeEnd"]);
//休憩開始
Route::post("/rest_start",[RestController::class,"restStart"]);
//休憩終了
Route::post("/rest_end", [RestController::class,"restEnd"]);
});