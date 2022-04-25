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
            $table->unsignedBigInteger('user_id');
            $table->integer('rest_id')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->nullable();
            $table->time('start_at');
            $table->time('end_at')->nullable();
            $table->time('work_at')->nullable();
            $table->date('date');
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