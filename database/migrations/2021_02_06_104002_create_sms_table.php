<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('接收人用户id');
            $table->string('phone',20)->nullable(false)->comment('phone');
            $table->mediumText('data')->nullable(false)->comment('短信消息参数');
            $table->string('message',500)->nullable(false)->comment('短信消息返回数据');
            $table->unsignedTinyInteger('status')->nullable(false)->default(1)->comment('发送状态 1 成功 0 失败 默认为1');
            $table->timestamp('created_at')->nullable()->comment('发送时间');
            $table->timestamp('updated_at')->nullable()->comment('更改时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms');
    }
}
