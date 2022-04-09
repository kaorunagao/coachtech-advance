<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;

class TimestampController extends Controller
{
    public function showTimestamp(){
        $user   = Auth::user();
        $end_time = Stamp::where('user_id', $user->id)
        ->where('date', Carbon::now()
        ->format("Y-m-d"))
        ->value('end_time');
        return view("timestamp",
        ["user"=>$user]);
    }
// 勤怠開始を記録する。
// 既に出勤している状態で出勤打刻を押した場合、メッセージで知らせる。
    public function timeStart() {
        $start_time = Stamp::where("user_id",Auth::user()->id)
        ->where("date",Carbon::today()
        ->format("Y-m-d"))
        ->value("start_time");
        if ($start_time == null) {
            Stamp::create([
                "user_id" =>Auth::id(),
                "date"    =>Carbon::now()->format('Y-m-d'),
                "start_time"=>Carbon::now()->format("H:i:s"),
            ]);
            return redirect("/")->with([
                    "message" =>"出勤記録しました。",
                    "start"   =>"true",
                    "rest_end"=>"true",
                ]);
        }
            return redirect("/")->with([
                    "message"  =>"出勤済",
                    "start"    =>"true",
                    "rest_rest"=>"true",
        ]);

    }
// 勤怠終了を記録すると同時に勤怠時間も計算する。
// 既に退勤している状態で退勤打刻を押した場合、
// メッセージで知らせる。
// 勤怠時間は差分の秒数を計算後、
// 時間/分/秒に切り分けて処理する
    public function timeEnd(){
        $user     = Auth::user();
        $today    = Carbon::today()->format('Y-m-d');
        $end_time = Stamp::where('user_id', $user->id)->where('date', $today)->value('end_at');
        if ($end_time !== null 
        // ||
        // Rest::where('data',Carbon::now())
        // ->where('stamp_id',Stamp::where('user_id',$user->id)
        // ->where("data",Carbon::today()->format("Y-m-d")))
        // ->value("end_at") == null
        ){
            return redirect("/")->with("message","退勤済または休憩中");
        }
        $work_total = Stamp::where('user_id', $user->id)
        ->where('date', $today)
        ->orderBy("id","desc")
        ->value('start_at')
        ->diffINSeconds(Carbon::now()->format("H:i:s"));
        // 1時間=3600秒であるため、秒数から時間を算出するために差分から3600の商を出す。
        $work_hour  = floor($work_total / 3600);
        $work_min   = floor(($work_total - 3600 * $work_hour) / 60);
        $work_sec   = floor($work_total % 60);
        // 条件式で○○:○○に合わせるようにする。
        $work_hour  = $work_hour < 10 ? "0" . $work_hour : $work_hour;
        $work_min   = $work_min < 10 ? "0" . $work_min : $work_min;
        $work_sec   = $work_sec < 10 ? "0" . $work_sec : $work_sec;
        $work_total = $work_hour . ":" . $work_min . ":" . $work_sec;
        Stamp::where("user_id",$user->id)
            ->where("date",$today)
            ->whereNull("end_at")
            ->update([
            "user_id"=>Auth::id(),
            "end_at" =>Carbon::now()->format("H:i:s"),
            "work_at"=>$work_total,
        ]);
        return redirect("/")->with([
            "message"   =>"退勤記録しました。",
            "end"       =>"true",
            "rest_start"=>"true",
            "rest_end"  =>"true",
        ]);
    }
}