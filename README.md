# 勤怠管理システム Atte (アット)
### Atteとは
企業での人事評価を目的に制作しました<br>
登録ユーザー(従業員)の勤怠状況を管理することができるWebアプリケーションです。<br>
### http://kintai-system.herokuapp.com

## 使用イメージ

![990FECDB-35AA-480A-8035-621430404F5E_1_102_o](https://user-images.githubusercontent.com/96050078/167598036-a4eabdc8-ed0f-4ea6-8eae-fd24cf690f48.jpeg)

勤怠状況をユーザーページにて打刻をすることで登録<br>
勤務開始を打刻した後に休憩開始、休憩終了、勤務終了のいずれかを打刻すると<br>
日付一覧ページにて日付別勤怠情報を確認することもできます。

![B9686172-31AE-472B-8FF6-1A085DED0C36_1_102_o](https://user-images.githubusercontent.com/96050078/167597938-064edbc3-9ae2-48fe-a7af-56f0108602d0.jpeg)

ログアウト→再ログインを行なっても、打刻した状況に合わせて画面表示が継続されるため<br>
いつどこにいても、勤怠状況の確認と打刻をすることが可能です。

## 動作環境

* HTML/CSS
* Javascrict
* PHP 8.1.2
* Laravel 8.83.6
* Laravel Breeze
* MySql 5.7.34