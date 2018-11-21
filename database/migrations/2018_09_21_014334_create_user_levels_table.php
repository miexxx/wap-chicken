<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('level')->unique()->comment('分享家等级，越大等级越高');
            $table->string('name', 32)->unique()->comment('等级名称');
            $table->unsignedInteger('money')->comment('升级推广消费金额');
            $table->unsignedInteger('sales_percent')->comment('销售佣金抽成');
            $table->unsignedInteger('invite_percent')->comment('邀请佣金抽成');
            //$table->tinyInteger('upgrade_way')->default(1)->comment('升级方式：1手动2自动');
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
        Schema::dropIfExists('user_levels');
    }
}
