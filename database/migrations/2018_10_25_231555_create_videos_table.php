<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('media_id')->unique()->comment('media_id');
            $table->string('name',50)->comment('视频名称');
            $table->string('description')->comment('视频介绍');
            $table->unsignedInteger('update_time')->comment('最近更新时间戳');
            $table->string('url',1024)->comment('视频路径');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
