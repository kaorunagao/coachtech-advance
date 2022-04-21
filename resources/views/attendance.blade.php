<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AttendanceSystem</title>
    <style>
    body{
      width:100%;
      margin:0;
      padding:0;
    }
    .header{
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding:20px 0 20px 30px;
      line-height:0px;
    }
    .header_nav{
      width:40%;
    }
    .header_nav ul{
      display:flex;
      justify-content:space-evenly;
    }
    .header_nav ul li{
      list-style:none;
    }
    .header_nav ul li a:hover{
      color:blue;
    }
    .header_nav a{
      text-decoration:none;
      color:black;
    }
    .date{
      display:flex;
      justify-content:center;
    }
    .date_today{
      font-weight:bold;
      font-size:20px;
      text-align:center;
      padding:30px;
      margin:3px;
    }
    .date_button{
      width:35px;
      margin:33px 25px 0;
      font-size:20px;
      background:white;
      border-color:blue;
      color:blue;
      cursor:pointer;
    }
    .content{
      width:100%;
      height:100%;
      background-color:#F5F5F5;
      min-height:calc(89vh - 50px);
    }
    .info_attendance{
      width:90%;
      margin:0 auto;
      font-size:17px;
    }
    table{
      text-align:center;
      border-collapse:collapse;
    }
    th,td {
      padding:15px;
      border-top:1px solid silver;
    }
    svg.w-5.h-5 {
      width: 30px;
      height: 30px;
    }
    .pagination {
      display:flex;
      justify-content:center;
      list-style:none;
    }
    .pagination li {
      background:white;
      width:30px;
      text-align:center;
      margin-bottom:2px;
      margin-right:1px;
    }
    .pagination li:hover{
      background: blue;
      color: white;
    }
    .page-item :hover{
      background: blue;
      color: white;
    }
    .pagination li a {
      display:block;
      height: 100%;
      text-decoration:none;
    }
    .nav-links{
      padding:10px 0;
    }
    .footer {
      width:100%;
      height:10px;
      margin-top:10px;
      text-align:center;
      font-weight:bold;
    }
    </style>
  </head>

  <body>
    <div class="header">
      <div class="header_title">
        <header><h1>Atte</h1></header>
      </div>
      <div class="header_nav">
        <ul>
          <li><a href="/">ホーム</a></li>
          <li><a href="/attendance">日付一覧</a></li>
          <form action="{{route('logout')}}" method="POST">
            @csrf
            <li>
              <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
              this.closest('form').submit();">{{ __('ログアウト') }}
              </x-dropdown-link>
            </li>
          </form>
        </ul>
      </div>
    </div>
    <div class="content">
      <div class="date">
        <form action="/attendance" method="POST">
          @csrf
          <input type="hidden" name="back" value="back">
          <input type="hidden" name="date" value={{$today}}>
          <button class="date_button"><</button>
        </form>
        <p class="date_today">{{$today}}</p>
        <form action="/attendance" method="POST">
          @csrf
          <input type="hidden" name="next" value="next">
          <input type="hidden" name="date" value={{$today}}>
          <button class="date_button">></button>
        </form>
      </div>
      <div class="info">
        <table class ="info_attendance">
          <tr>
            <th>名前</th>
            <th>勤務開始</th>
            <th>勤務終了</th>
            <th>休憩時間</th>
            <th>勤務時間</th>
          </tr>
          @foreach($stamps as $stamp)
          <tr>
            <td>{{$stamp->user->name}}</td>
            <td>{{$stamp->start_at->format("H:i:s")}}</td>
            <td>{{$stamp->end_at}}</td>
            <td>{{$stamp->rest->total_at->format("H:i:s")}}</td>
            <td>{{$stamp->work_at}}</td>
          </tr>
          @endforeach
        </table>
        <div class="nav-links">
          {{$stamps->appends(['date' => $today ])->links()}}
        </div>
      </div>
    </div>
    <div class="footer">
      <footer>Atte,inc.</footer>
    </div>
  </body>
  </html>