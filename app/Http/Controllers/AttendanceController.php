<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Rest;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class AttendanceController extends Controller
{
// 本日勤務しているユーザーを表示する
// 1ページ5人を出力し、ページで管理する
    public function showAttendance(Request $request){
        $date   = $request->input('date')
        ?: Carbon::now()->format('Y-m-d');
        $stamps = Attendance::whereDate('date',$date)
        ->orderBy('user_id','asc')
        ->Paginate(5);
        return view("attendance",[
            "today"  => $date,
            "stamps" => $stamps
        ]);
    }

// 日付サイドの｢</>｣を押すと日付の1日前/1日後のデータを表示する
// < =　値｢back」を受け取り1日前に戻る
// > =　値｢next｣を受け取り1日後に進む
    public function getAttendance(Request $request){
        // 1日前の処理
        if($request->input('back') == 'back'){
            $day = date('Y-m-d',strtotime('-1day',strtotime($request->input('date'))));
            $stamps = Attendance::whereDate('date',$day)
            ->orderBy('user_id','asc')
            ->Paginate(5);
        }
        // 1日後の処理
        if($request->input('next') == 'next'){
            $day = date('Y-m-d',strtotime('+1day',strtotime($request->input('date'))));
            $stamps = Attendance::whereDate('date',$day)
            ->orderBy('user_id','asc')
            ->Paginate(5);
        }
        return view("/attendance")->with([
            "today" =>$day,
            "stamps"=>$stamps,
        ]);
    }
}