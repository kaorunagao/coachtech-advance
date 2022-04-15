<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AttendanceSystem</title>
    <style>
    body {
      width:100%;
      margin:0;
      padding:0;
    }
    header {
      padding-left:50px;
    }
    .content {
      width:100%;
      background-color:#F5F5F5;
      padding:20px 0 100px 0;
      min-height:calc(70vh - 50px);
    }
    table {
      width:100%;
      margin:0 auto;
      text-align:center;
    }
    td {
      padding:5px;
      display:flex;
      justify-content:center;
    }
    .content_input-p {
      margin:0;
    }
    .content_input-p:first-child {
      color:grey;
      font-size:12px;
    }
    .content_input-a {
      margin:0;
      color:blue;
      text-decoration:none;
    }
    input {
      width:300px;
      height:25px;
      padding:7px;
      margin:5px 0;
      background-color:#F5F5F5;
      border:1px solid grey;
      border-radius:3px;
    }
    .content_input-button {
      width:315px;
      height:40px;
      padding:7px;
      background:blue;
      color:white;
      border:none;
      border-radius:3px;
      cursor:pointer;
    }
    .content_input-button:hover {
      background:#00008B;
      color:blue;
    }
    .content_input-a:hover {
      color:#00008B;
    }
    .error {
      color:red;
      font-size:5px;
    }
    footer {
      width:100%;
      height:10px;
      margin-top:15px;
      text-align:center;
      font-weight:bold;
    }
    </style>
  </head>
    
  <body>
    <div class="header">
      <header><h2>Atte</h2></header>
    </div>
    <div class="content">
      <table>
        <tr>
          <td>
            <h3>ログイン</h3>
          </td>
          <div class="content_input">
            <form action="/login" method="post">
              @csrf
              @error("email")
              <td class="error">{{$message}}</td>
              @enderror
              <td>
                <input type="email" name="email" placeholder="  メールアドレス" value="{{old('email')}}">
              </td>
              @error("password")
              <td class="error">{{$message}}</td>
              @enderror
              <td>
                <input type="password" name="password" placeholder="  パスワード">
              </td>
              <td>
                <input type="submit" value="ログイン" class="content_input-button">
              </td>
              <td>
                <p class="content_input-p">アカウントをお持ちでない方はこちらから</p>
              </td>
              <td>
                <p class="content_input-p">
                  <a href="/register" class="content_input-a">会員登録</a>
                </p>
              </td>
            </form>
          </div>
        </tr>
      </table>
    </div>
    <div class="footer">
      <footer>Atte,inc.</footer>
    </div>
  </body>
</html>