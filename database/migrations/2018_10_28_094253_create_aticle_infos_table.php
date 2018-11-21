<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAticleInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aticle_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('article_id')->index();
            $table->string('title',64)->comment('标题');
            $table->string('author',10)->comment('作者');
            $table->string('digest',120)->comment('文章摘要');
            $table->string('url',1024)->comment('图文地址');
            $table->string('thumb_url',1024)->comment('封面图地址');
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
        Schema::dropIfExists('aticle_infos');
    }
}
