<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Rest;
use App\Models\Stamp;
use App\Utils\Utility;

class TimestampController extends Controller
{
    public function showSession(){
        $user   = Auth::user();
        $start = null;
        $end = null;
        $rest_start = null;
        $rest_end = null;

        $stamp = Stamp::where('user_id', $user->id);
        $stamp_updated_at = new Carbon($stamp->value('updated_at'));

        // スタンプが記録されていてかつ、更新日が本日の場合にStamp情報を取得し、セッションを更新する
        if($stamp->exists() && $stamp_updated_at->isToday()){

            if ($stamp->value('attendance') == true ){
                // 出勤
                $start = true;
            }else{
                // 退勤
                $start = true;
                $end = true;
                $rest_start = true;
                $rest_end = true;
            }
            if ($stamp->value('rest') == true ){
                // 休憩開始
                $start = true;
                $end = true;
                $rest_start = true;
            }else{
                // 休憩終了
                $start = true;
                $rest_end = true;
            }
        }
        session()->put('start',$start);
        session()->put('end',$end);
        session()->put('rest_start',$rest_start);
        session()->put('rest_end',$rest_end);
        session()->save();

        return view("timestamp",["user"=>$user]);
    }

    public function getTimestamp(){
        $user   = Auth::user(); 
        $end_at = Attendance::where('user_id', $user->id)
        ->where('date', Carbon::now()
        ->format('Y-m-d'))
        ->value('end_at');

        return view("timestamp",["user"=>$user]);
    }

// 勤務開始を記録する
    public function timeStart() {
        $start_time = Attendance::where('user_id',Auth::user()->id)
        ->where('date',Carbon::today()
        ->format('Y-m-d'))
        ->value('start_at');
        if ($start_time == null) 
        {
            Attendance::create([
                'user_id' =>Auth::id(),
                'date'    =>Carbon::now()->format('Y-m-d'),
                'start_at'=>Carbon::now()->format('H:i:s'),
            ]);
            
            //押されたボタンの状態をDBに登録する
            Utility::registerStamp(Auth::id(),true,false);
            
            return redirect("/")->with([
                session()->put('message','勤務開始を記録しました'),
                session()->put('start',"true"),
                session()->put('end',null),
                session()->put('rest_start',null),
                session()->put('rest_end',"true"),
                session()->save(),
            ]);
        }
    }
// 勤務終了を記録すると同時に勤務時間を計算する
// 勤務時間は差分の秒数を計算後、時間/分/秒に切り分けて処理する
    public function timeEnd(){
        $user     = Auth::user();
        $today    = Carbon::today()->format('Y-m-d');
        $end_time = Attendance::where('user_id', $user->id)->where('date', $today)->value('end_at');
        $start_time = Attendance::where('user_id',Auth::user()->id)
        ->where('date',Carbon::today()
        ->format('Y-m-d'))
        ->value('start_at');
        if ($start_time == null) 
        {
            return redirect("/error");
        }
        elseif (!empty( Attendance::where('user_id', $user->id)->where('date', $today)->value('rest_id')))
        {
        $work_total = Attendance::where('user_id', $user->id)
        ->where('date', $today)
        ->orderBy('id','desc')
        ->value('start_at')
        ->diffINSeconds(Carbon::now()->format('H:i:s'));
        // 1時間=3600秒であるため、秒数から時間を算出するために差分から3600の商を出す
        $work_hour  = floor($work_total / 3600);
        $work_min   = floor(($work_total - 3600 * $work_hour) / 60);
        $work_sec   = floor($work_total % 60);
        // 条件式で○○:○○に合わせるようにする
        $work_hour  = $work_hour < 10 ? "0" . $work_hour : $work_hour;
        $work_min   = $work_min < 10 ? "0" . $work_min : $work_min;
        $work_sec   = $work_sec < 10 ? "0" . $work_sec : $work_sec;
        $work_total = $work_hour . ":" . $work_min . ":" . $work_sec;
        // 休憩時間を差し引いた勤務時間
        $attendance_total = Rest::where('attendance_id',Attendance::where('user_id',Auth::user()->id)
        ->latest()
        ->first()
        ->id)
        ->orderby('created_at','desc')
        ->value('total_at')
        ->diffINSeconds($work_total);
        $attendance_hour  = floor($attendance_total / 3600);
        $attendance_min   = floor(($attendance_total - 3600 * $attendance_hour) / 60);
        $attendance_sec   = floor($attendance_total % 60);
        $attendance_hour  = $attendance_hour < 10 ? "0" . $attendance_hour : $attendance_hour;
        $attendance_min   = $attendance_min < 10 ? "0" . $attendance_min : $attendance_min;
        $attendance_sec   = $attendance_sec < 10 ? "0" . $attendance_sec : $attendance_sec;
        $attendance_total = $attendance_hour . ":" . $attendance_min . ":" . $attendance_sec;
        Attendance::where('user_id',$user->id)
            ->where('date',$today)
            ->whereNull('end_at')
            ->update([
            'user_id'=>Auth::id(),
            'end_at' =>Carbon::now()->format('H:i:s'),
            'work_at'=>$attendance_total,
            ]);
            
            //押されたボタンの状態をDBに登録する
            Utility::registerStamp(Auth::id(),false,false);
            
            return redirect("/")->with([
                session()->put('message','勤務終了を記録しました'),
                session()->put('start',"true"),
                session()->put('end',"true"),
                session()->put('rest_start',"true"),
                session()->put('rest_end',"true"),
                session()->save(),
            ]);
        }
        $work_total = Attendance::where('user_id', $user->id)
        ->where('date', $today)
        ->orderBy('id','desc')
        ->value('start_at')
        ->diffINSeconds(Carbon::now()->format('H:i:s'));
        // 1時間=3600秒であるため、秒数から時間を算出するために差分から3600の商を出す
        $work_hour  = floor($work_total / 3600);
        $work_min   = floor(($work_total - 3600 * $work_hour) / 60);
        $work_sec   = floor($work_total % 60);
        // 条件式で○○:○○に合わせるようにする
        $work_hour  = $work_hour < 10 ? "0" . $work_hour : $work_hour;
        $work_min   = $work_min < 10 ? "0" . $work_min : $work_min;
        $work_sec   = $work_sec < 10 ? "0" . $work_sec : $work_sec;
        $work_total = $work_hour . ":" . $work_min . ":" . $work_sec;
        Rest::create([
            'attendance_id'=>Attendance::where('user_id',$user->id)
            ->orderBy('id','desc')
            ->first()
            ->id,
            'date'    =>$today,
            'start_at'=>Carbon::today()->format('H:i:s'),
            'end_at'  =>Carbon::today(),
            'total_at'=>Carbon::today(),
        ]);
        Attendance::where('user_id',$user->id)
            ->where('date',$today)
            ->whereNull('end_at')
            ->update([
            'user_id'=>Auth::id(),
            'end_at' =>Carbon::now()->format('H:i:s'),
            'work_at'=>$work_total,
            'rest_id'=>Rest::where('attendance_id',Attendance::where('user_id',$user->id)
        ->orderBy('id','desc')
        ->first()->id)
            ->orderBy('created_at','desc')
            ->value('id')
        ]);
        
        //押されたボタンの状態をDBに登録する
        Utility::registerStamp(Auth::id(),false,false);
        
        return redirect("/")->with([
            session()->put('message','勤務終了を記録しました'),
            session()->put('start',"true"),
            session()->put('end',"true"),
            session()->put('rest_start',"true"),
            session()->put('rest_end',"true"),
            session()->save(),
        ]);
    }
}