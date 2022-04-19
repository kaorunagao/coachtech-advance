<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Rest;

class TimestampController extends Controller
{
    public function showTimestamp(){
        $user   = Auth::user();
        $end_at = Attendance::where("user_id", $user->id)
        ->where("date", Carbon::now()
        ->format("Y-m-d"))
        ->value("start_at");
        return view("timestamp",["user"=>$user]);
    }

// 勤務開始を記録する
// 既に勤務開始を押している状態で勤務開始を押した場合、メッセージで知らせる
    public function timeStart() {
        $start_time = Attendance::where("user_id",Auth::user()->id)
        ->where("date",Carbon::today()
        ->format("Y-m-d"))
        ->value("start_at");
        if ($start_time == null) {
            Attendance::create([
                "user_id" =>Auth::id(),
                "date"    =>Carbon::now()->format("Y-m-d"),
                "start_at"=>Carbon::now()->format("H:i:s"),
            ]);
            return redirect("/")->with([
                    "message" =>'勤務開始を記録しました',
                    "start"   =>"true",
                    "rest_end"=>"true",
                ]);
        }
            return redirect("/")->with([
                    "message"  =>'勤務開始済みです',
                    "start"    =>"true",
                    "rest_end"=>"true",
        ]);
    }

// 勤務終了を記録すると同時に勤務時間を計算する
// 既に勤務終了を押している状態で勤務終了を押した場合、メッセージで知らせる
// 勤務時間は差分の秒数を計算後、時間/分/秒に切り分けて処理する
    public function timeEnd(){
        $user     = Auth::user();
        $today    = Carbon::today()->format("Y-m-d");
        $end_time = Attendance::where("user_id", $user->id)->where("date", $today)->value("end_at");
        if ($end_time !== null){
            return redirect("/")->with([
                "message"   =>'勤務終了済みです',
                "start"     =>"true",
                "end"       =>"true",
                "rest_start"=>"true",
                "rest_end"  =>"true",
            ]);
        }
        $work_total = Attendance::where("user_id", $user->id)
        ->where("date", $today)
        ->orderBy("id","desc")
        ->value("start_at")
        ->diffINSeconds(Carbon::now()->format("H:i:s"));
        // 1時間=3600秒なので、秒数から時間を算出するために差分から3600秒の商を出す
        $work_hour  = floor($work_total / 3600);
        $work_min   = floor(($work_total - 3600 * $work_hour) / 60);
        $work_sec   = floor($work_total % 60);
        // 条件式で○○:○○に合わせるようにする
        $work_hour  = $work_hour < 10 ? "0" . $work_hour : $work_hour;
        $work_min   = $work_min < 10 ? "0" . $work_min : $work_min;
        $work_sec   = $work_sec < 10 ? "0" . $work_sec : $work_sec;
        $work_total = $work_hour . ":" . $work_min . ":" . $work_sec;
        Attendance::where("user_id",$user->id)
            ->where("date",$today)
            ->whereNull("end_at")
            ->update([
            "user_id"=>Auth::id(),
            "end_at" =>Carbon::now()->format("H:i:s"),
            "work_at"=>$work_total
        ]);
        return redirect("/")->with([
            "message"   =>'勤務終了を記録しました',
            "start"     =>"true",
            "end"       =>"true",
            "rest_start"=>"true",
            "rest_end"  =>"true",
        ]);
    }
}