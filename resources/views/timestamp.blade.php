<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atte</title>
    <style>
    body {
      width:100%;
      margin:0;
      padding:0;
    }
    .header {
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding:20px 0 0 30px;
      line-height:0px;
    }
    .header_nav {
      width:100%;
    }
    .header_nav ul {
      display:flex;
      justify-content:flex-end;
      
    }
    .header_nav ul li {
      list-style:none;
      padding:0 20px;
    }
    .header_nav ul li a:hover {
      color:blue;
    }
    .header_nav a {
      text-decoration:none;
      color:black;
    }
    .user_list-title {
      font-weight:bold;
      font-size:20px;
      text-align:center;
      padding-top:30px;
    }
    .content {
      width:100%;
      height:100%;
      background-color:#F5F5F5;
      min-height:calc(88vh - 50px);
    }
    .content_stampbtn {
      width:100%;
      display:flex;
      justify-content:center;
      padding:25px 0;
    }
    .content button {
      border:none;
      padding:60px 100px;
      margin:-10px 20px;
      background:white;
      font-weight:bold;
      font-size:16px;
    }
    .btn_disabled {
      color:#dcdcdc;
    }
    .session {
      text-align:center;
      font-size:15px;
      font-weight:bold;
      color:blue;
    }
    .footer {
      width:100%;
      height:10px;
      margin-top:10px;
      text-align:center;
      font-weight:bold;
    }
    @media screen and (max-width: 480px) {
    body {
      width:100%;
      margin:0;
      padding:0;
    }
    .header {
      width:100%;
      padding:0;
      display:flex;
      justify-content:space-between;
      align-items:center;
      line-height:0px;
    }
    .header_title {
      font-size:10px;
      padding:20px 0 0 20px;
    }
    .header_nav {
      width:100%;
      font-size:8px;
      margin-top:25px;
    }
    .header_nav ul {
      display:flex;
      justify-content:space-evenly;
    }
    .header_nav ul li {
      list-style:none;
    }
    .header_nav ul li a:hover {
      color:blue;
    }
    .header_nav a {
      text-decoration:none;
      color:black;
    }
    .user_list-title {
      font-weight:bold;
      font-size:20px;
      text-align:center;
      padding-top:30px;
    }
    .content {
      width:100%;
      height:100%;
      margin:0;
      padding:0;
      background-color:#F5F5F5;
      min-height:calc(90vh - 50px);
    }
    .content_stampbtn {
      display:flex;
      justify-content:center;
      padding:20px 0;
      margin-top:10px;
    }
    .content button {
      border:none;
      padding:20px 40px;
      margin:-15px 10px;
      background:white;
      font-weight:bold;
      font-size:15px;
    }
    .btn_disabled {
      color:#dcdcdc;
    }
    .session {
      text-align:center;
      font-size:15px;
      font-weight:bold;
      color:blue;
    }
    .footer {
      width:100%;
      height:10px;
      margin-top:20px;
      text-align:center;
      font-weight:bold;
    }
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
      <div class="user_list">
        <p class="user_list-title">{{$user->name}}さんお疲れ様です！</p>
      </div>
      <div class="session">
        <p>
          {{session('message')}}
        </p>
      </div>
      <div class="content_stampbtn" id="btn_start">
        <form action="/time_start" method="POST">
          {{--勤務開始--}}
          @if(Session::has('start'))
          @csrf
          <button type="submit" class="btn_disabled" id="btn_start_1" disabled>
            勤務開始
          </button>
          @else
          @csrf
          <button type="submit" class="btn" id="btn_start_2">
            勤務開始
          </button>
          @endif
        </form>
        <form action="/time_end" method="POST">
          {{--勤務終了--}}
          @if(Session::has('end'))
          @csrf
          <button type="submit" class="btn_disabled" id="btn_start_3" disabled>
            勤務終了
          </button>
          @else
          @csrf
          <button type="submit" class="btn" id="btn_start_4">
            勤務終了
          </button>
          @endif
        </form>
      </div>
      <div class="content_stampbtn" id="btn_rest">
        <form action="/rest_start" method="POST">
          {{--休憩開始--}}
          @if(Session('rest_start'))
          @csrf
          <button type="submit" class="btn_disabled" id="btn_rest_1" disabled>
            休憩開始
          </button>
          @else
          @csrf
          <button type="submit" class="btn" id="btn_rest_2">
            休憩開始
          </button>
          @endif
        </form>
        <form action="/rest_end" method="POST">
          {{--休憩終了--}}
          @if(Session('rest_end'))
          @csrf
          <button type="submit" class="btn_disabled" id="btn_rest_3" disabled>
            休憩終了
          </button>
          @else
          @csrf
          <button type="submit" class="btn" id="btn_rest_4">
            休憩終了
          </button>
          @endif
        </form>
      </div>
    </div>
    <div class="footer">
      <footer>Atte,inc.</footer>
    </div>
  </body>
</html>