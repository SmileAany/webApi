<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     * 发送邮件记录器
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(false)->comment('接收人用户id');
            $table->string('email',100)->nullable(false)->comment('邮件地址');
            $table->mediumText('data')->nullable(false)->comment('邮件参数信息');
            $table->string('message',500)->nullable(false)->comment('邮件发送返回信息');
            $table->unsignedTinyInteger('status')->nullable(false)->default(1)->comment('发送状态 1 成功 0 失败 默认为1');
            $table->timestamp('created_at')->nullable(true)->comment('发送时间');
            $table->timestamp('updated_at')->nullable(true)->comment('更改时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emails');
    }
}
