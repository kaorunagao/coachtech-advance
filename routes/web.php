<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimestampController;
use App\Http\Controllers\RestController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RegisteredUserController;

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


// 打刻状況、表示/処理
Route::get('/', [TimestampController::class, "showSession"])->middleware(["auth"]);
Route::post('/', function () {
    [TimestampController::class,"getTimestamp"];
})->middleware(["auth"]);


Route::group(['middleware' => 'auth'], function () {
//勤務開始
Route::post("/time_start",[TimestampController::class,"timeStart"]);
// 勤務終了
Route::post("/time_end",[TimestampController::class,"timeEnd"]);
//休憩開始
Route::post("/rest_start",[RestController::class,"restStart"]);
//休憩終了
Route::post("/rest_end", [RestController::class,"restEnd"]);

// 日別勤怠管理、表示/処理
Route::get("/attendance",  [AttendanceController::class,"showAttendance"]);
Route::post("/attendance", [AttendanceController::class,"getAttendance"]);

});