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
    header {
      padding-left:50px;
    }
    .content {
      width:100%;
      background-color:#F5F5F5;
      min-height:calc(88vh - 50px);
    }
    table {
      width:100%;
      margin:0 auto;
      text-align:center;
    }
    td {
      padding:7px;
      display:flex;
      justify-content:center;
    }
    .content_input-p {
      margin:0;
      font-size:15px;
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
      width:310px;
      height:35px;
      background-color:#F5F5F5;
      border:1px solid grey;
      border-radius:3px;
    }
    .content_input-button {
      width:315px;
      height:35px;
      padding:3px;
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
    .footer {
      width:100%;
      height:10px;
      margin-top:17px;
      text-align:center;
      font-weight:bold;
    }
    @media screen and (max-width: 480px) {
    body {
      width:100%;
      margin:0;
      padding:0;
    }
    header {
      padding-left:30px;
    }
    .content {
      width:100%;
      background-color:#F5F5F5;
      min-height:calc(92vh - 50px);
    }
    table {
      width:100%;
      margin:0 auto;
      text-align:center;
    }
    td {
      padding:7px;
      display:flex;
      justify-content:center;
    }
    .content_input-p {
      margin:0;
      font-size:15px;
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
      width:310px;
      height:35px;
      background-color:#F5F5F5;
      border:1px solid grey;
      border-radius:3px;
    }
    .content_input-button {
      width:315px;
      height:35px;
      padding:3px;
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
    .footer {
      width:100%;
      height:10px;
      margin-top:10px;
      text-align:center;
      font-weight:bold;
    }
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
            <h3>会員登録</h3>
          </td>
          <div class="content_input">
            <form action="/register" method="post">
              @csrf
              @error("name")
              <td class="error">{{$message}}</td>
              @enderror
              <td>
                <input type="text" name="name" placeholder="  名前" value="{{old('name')}}">
              </td>
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
                <input type="password" name="password" placeholder="  パスワード" value="{{old('password')}}">
              </td>            
              <td>
                <input type="password" name="password_confirmation" placeholder="  確認用パスワード">
              </td>
              @error("password_confirmation")
              <td class="error">{{$message}}</td>
              @enderror
              <td>
                <input type="submit" value="会員登録" class="content_input-button">
              </td>
              <td>
                <p class="content_input-p">アカウントをお持ちの方はこちらから</p>
              </td>
              <td>
                <p class="content_input-p">
                  <a href="/login" class="content_input-a">ログイン</a>
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