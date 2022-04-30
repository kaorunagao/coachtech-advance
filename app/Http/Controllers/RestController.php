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

class RestController extends Controller
{

// 休憩開始の記録をする
    public function restStart(){
        $user       = Auth::user();
        $today      = Carbon::today()->format('Y-m-d');
        $stamp      = Attendance::where('user_id',$user->id)
        ->orderBy('id','desc')
        ->first();
        $stamp_test = Attendance::where('user_id',$user->id)
        ->latest()
        ->first();
        $rest       = Rest::where('attendance_id',$stamp->id)
        ->orderBy('created_at','desc')
        ->first();
        if (!empty($stamp->end_at))
        {
            return redirect("/")->with([
                session()->put('message','まずは勤務開始を打刻して下さい'),
                session()->put('start',null),
                session()->put('end',"true"),
                session()->put('rest_start',"true"),
                session()->put('rest_end',"true"),
                session()->save(),
            ]);
        }
        elseif (empty($rest)){
            $rest_at = Rest::create([
                'attendance_id'=>$stamp->id,
                'date'         =>$today,
                'start_at'     =>Carbon::now(),
            ]);
            Attendance::where('user_id',$user->id)
            ->orderBy('id','desc')
            ->first()
            ->update([
                'rest_id' => Rest::where('attendance_id',$stamp->id)
                ->orderBy('created_at','desc')
                ->value('id')
            ]);

            //押されたボタンの状態をDBに登録する
            Utility::registerStamp(Auth::id(),true,true);

            return redirect("/")->with([
                session()->put('message','休憩開始を記録しました'),
                session()->put('start',"true"),
                session()->put('end',"true"),
                session()->put('rest_start',"true"),
                session()->put('rest_end',null),
                session()->save(),
            ]);
        }
        elseif (!empty($rest->end_at)){
            $rest_at = Rest::where('attendance_id',$stamp->id)
            ->orderBy('created_at','desc')
            ->update([
                'attendance_id'=>$stamp->id,
                'date'         =>$today,
                'start_at'     =>Carbon::now()->format('H:i:s'),
            ]);

            //押されたボタンの状態をDBに登録する
            Utility::registerStamp(Auth::id(),true,true);

            return redirect("/")->with([
                session()->put('message','休憩開始を記録しました'),
                session()->put('start',"true"),
                session()->put('end',"true"),
                session()->put('rest_start',"true"),
                session()->put('rest_end',null),
                session()->save(),
            ]);
        }
    }

// 休憩終了を記録すると同時に休憩時間を計算する
// 複数休憩時間を取得する場合、上書きで管理していく
// 2回目以降の休憩は休憩時間を時/分/秒に分けてそれぞれ計算し結合させる
    public function restEnd(){
        $user  = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        $stamp = Attendance::where('user_id',Auth::user()->id)
        ->latest()
        ->first();
        $rest  = Rest::where('attendance_id',$stamp->id)
        ->orderBy('created_at','desc')
        ->first();
        if (empty(Rest::where('attendance_id',$stamp->id)
        ->orderBy('created_at','desc')
        ->first()->end_at)){
        $rest_total = Rest::where('attendance_id',$stamp->id)
        ->orderby('created_at','desc')
        ->value('start_at')
        ->diffINSeconds(Carbon::now()->format('H:i:s'));
        $rest_hour  = floor($rest_total / 3600);
        $rest_min   = floor(($rest_total - 3600 * $rest_hour) / 60);
        $rest_sec   = floor($rest_total % 60);
        $rest_hour  = $rest_hour < 10 ? "0" . $rest_hour : $rest_hour;
        $rest_min   = $rest_min < 10 ? "0" . $rest_min : $rest_min;
        $rest_sec   = $rest_sec < 10 ? "0" . $rest_sec : $rest_sec;
        $rest_total = $rest_hour . ":" . $rest_min . ":" . $rest_sec;
        $rest_at = Rest::where('attendance_id',$stamp->id)
        ->orderBy('created_at','desc')
        ->whereNull('end_at')
        ->update([
            'attendance_id'=>$stamp->id,
            'end_at'       =>Carbon::now()->format('H:i:s'),
            'total_at'     =>$rest_total,
        ]);

        //押されたボタンの状態をDBに登録する
        Utility::registerStamp(Auth::id(),true,false);

        return redirect("/")->with([
            session()->put('message','休憩終了を記録しました'),
            session()->put('start',"true"),
            session()->put('end',null),
            session()->put('rest_start',null),
            session()->put('rest_end',"true"),
            session()->save(),
        ]);
        }
        elseif (!empty($rest->end_at) && !empty($stamp->end_at))
        {
            return redirect("/")->with([
                session()->put('message','まずは勤務開始を打刻して下さい'),
                session()->put('start',null),
                session()->put('end',"true"),
                session()->put('rest_start',"true"),
                session()->put('rest_end',"true"),
                session()->save(),
            ]);
        }
        elseif (!empty($rest->end_at)){
            $rest_total = Rest::where('attendance_id',$stamp->id)
            ->orderBy('created_at','desc')
            ->value('start_at')
            ->diffINSeconds(Carbon::now());
            $rest_hour  = floor($rest_total / 3600);
            $rest_min   = floor(($rest_total - 3600 * $rest_hour) / 60);
            $rest_sec   = floor($rest_total % 60);
            $rest_hour  = $rest_hour < 10 ? "0" . $rest_hour : $rest_hour;
            $rest_min   = $rest_min < 10 ? "0" . $rest_min : $rest_min;
            $rest_sec   = $rest_sec < 10 ? "0" . $rest_sec : $rest_sec;
            $rest_total = $rest_hour . ":" . $rest_min . ":" . $rest_sec;
            // 休憩時間の更新
            $rest_previous_total = Carbon::today()
            ->diffInSeconds(Rest::where('attendance_id',$stamp->id)
            ->orderBy('created_at','desc')
            ->value('total_at')
            ->format('H:i:s'));
            $test_hour  = floor(floor($rest_previous_total / 3600) + $rest_hour);
            $test_min   = floor(floor($rest_previous_total - 3600 * $test_hour) / 60 + $rest_min);
            $test_sec   = floor(floor($rest_previous_total % 60) + $rest_sec);
            $test_hour  = $test_hour < 10 ? "0" . $test_hour : $test_hour;
            $test_min   = $test_min < 10 ? "0" . $test_min : $test_min;
            $test_sec   = $test_sec < 10 ? "0" . $test_sec : $test_sec;
            $test_total = $test_hour . ":" . $test_min . ":" . $test_sec;
            $test_at    = Rest::where('attendance_id',$stamp->id)
            ->orderBy('created_at','desc')
            ->update([
                'attendance_id'=>$stamp->id,
                'end_at'       =>Carbon::now()->format('H:i:s'),
                'total_at'     =>$test_total
            ]);

            //押されたボタンの状態をDBに登録する
            Utility::registerStamp(Auth::id(),true,false);

            return redirect("/")->with([
                session()->put('message','休憩終了を記録しました'),
                session()->put('start',"true"),
                session()->put('end',null),
                session()->put('rest_start',null),
                session()->put('rest_end',"true"),
                session()->save(),
            ]);
        }
    }
}