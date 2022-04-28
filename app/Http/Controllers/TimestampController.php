<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Rest;
use App\Models\Stamp;

class TimestampController extends Controller
{
    public function showTimestamp(){
        $user   = Auth::user(); 
        $end_at = Attendance::where('user_id', $user->id)
        ->where('date', Carbon::now()
        ->format('Y-m-d'))
        ->value('end_at');

        $start = null;
        $end = null;
        $rest_start = null;
        $rest_end = null;
        $attendance = Stamp::where('user_id',$user->id)->value('attendance');
        $rest = Stamp::where('user_id',$user->id)->value('rest');
        
        if ($attendance == true ){
            $start = true;
        }else{
            $start = true;
            $end = true;
            $rest_start = true;
            $rest_end = true;
        }

        if ($rest == true ){
            $rest_start = true;
        }else{
            $start = true;
            $end = true;
            $rest_start = true;
            $rest_end = true;
        }
        
        return view("timestamp",["user"=>$user])->with([
            session()->put('start',$start),
            session()->put('end',$end),
            session()->put('rest_start',$rest_start),
            session()->put('rest_end',$rest_end),
            session()->save(),
        ]);
    }
        
    public function registerStamp($user_id,$attendance,$rest){
        Stamp::upsert([
            'user_id'    =>$user_id,
            'attendance' =>$attendance,
            'rest'       =>$rest,],
            ['user_id']);
        }

// 勤務開始を記録する
// 既に勤務開始の打刻をしている状態で勤務開始の打刻をした場合、エラーで知らせる
    public function timeStart() {
        $start_time = Attendance::where('user_id',Auth::user()->id)
        ->where('date',Carbon::today()
        ->format('Y-m-d'))
        ->value('start_at');
        if ($start_time == null) {
            Attendance::create([
                'user_id' =>Auth::id(),
                'date'    =>Carbon::now()->format('Y-m-d'),
                'start_at'=>Carbon::now()->format('H:i:s'),
            ]);
            
            //押されたボタンの状態をDBに登録する
            registerStamp(Auth::id(),true,false);

            return redirect("/")->with([
                    session()->put('message','勤務開始を記録しました'),
                    session()->put('start',"true"),
                    session()->put('end',null),
                    session()->put('rest_start',null),
                    session()->put('rest_end',"true"),
                    session()->save(),
                ]);
        }
        return redirect("/error");
    }
// 勤務終了を記録すると同時に勤務時間を計算する
// 既に勤務終了を打刻している状態で勤務終了の打刻をした場合、エラーで知らせる
// 勤務時間は差分の秒数を計算後、時間/分/秒に切り分けて処理する
    public function timeEnd(){
        $user     = Auth::user();
        $today    = Carbon::today()->format('Y-m-d');
        $end_time = Attendance::where('user_id', $user->id)->where('date', $today)->value('end_at');
        if ($end_time !== null)
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
            registerStamp(Auth::id(),true,false);
            
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
        registerStamp(Auth::id(),true,false);
        
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