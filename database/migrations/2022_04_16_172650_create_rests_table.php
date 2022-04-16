<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_id'); //外部キー
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->nullable();
            $table->time('start_at');
            $table->time('end_at')->nullable();
            $table->time('total_at')->nullable();
            $table->date('date')->nullable();
            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
            //attendance_idに外部キー制約,attendancesテーブルのidカラムを参照してそのカラムが削除された場合カスケード的に処理
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rests');
    }
}