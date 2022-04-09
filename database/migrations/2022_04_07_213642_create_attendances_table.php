<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); //外部キー
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->nullable();
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->time('total_rest')->nullable();
            $table->time('total_time')->nullable();
            $table->date('attendance_date');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //user_idに外部キー制約,usersテーブルのidカラムを参照してそのカラムが削除された場合カスケード的に処理
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
