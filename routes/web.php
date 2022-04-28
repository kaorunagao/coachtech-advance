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

Route::get('/', function () {
    $user = Auth::user();
    return view("timestamp",["user"=>$user])->with([
            session()->put('start',$start),
            session()->put('end',$end),
            session()->put('rest_start',$rest_start),
            session()->put('rest_end',$rest_end),
            session()->save(),
        ]);
})->middleware(["auth"]);
// 打刻、表示/処理
Route::post('/', function () {
    [TimestampController::class,"showTimestamp"];
})->middleware(["auth"]);
Route::get('/', function ($user_id,$attendance,$rest) {
    [TimestampController::class,"registerStamp"];
})->middleware(["auth"]);


Route::group(['middleware' => 'auth'], function () {
//勤怠開始
Route::post("/time_start",[TimestampController::class,"timeStart"]);
Route::get("/time_start",[TimestampController::class,"registerStamp"]);
// 勤怠終了
Route::post("/time_end",[TimestampController::class,"timeEnd"]);
Route::get("/time_end",[TimestampController::class,"registerStamp"]);
//休憩開始
Route::post("/rest_start",[RestController::class,"restStart"]);
Route::get("/rest_start",[RestController::class,"registerStamp"]);
//休憩終了
Route::post("/rest_end", [RestController::class,"restEnd"]);
Route::get("/rest_end", [RestController::class,"registerStamp"]);

// 日別勤怠管理、表示/処理
Route::get("/attendance",  [AttendanceController::class,"showAttendance"]);
Route::post("/attendance", [AttendanceController::class,"getAttendance"]);

});