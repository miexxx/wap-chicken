<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderStageItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('order_stage_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->unsignedInteger('item_id')->comment('商品ID');
            $table->unsignedInteger('count')->comment('件数');
            $table->unsignedInteger('price')->comment('单价(分)');
            $table->unsignedInteger('type')->comment('0表示周配送 1表示月配送');
            $table->unsignedInteger('num')->comment('每次配送数量');
            $table->unsignedInteger('time')->comment('连续配送时长');
            $table->timestamp('first_time')->comment('第一次配送时间');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
