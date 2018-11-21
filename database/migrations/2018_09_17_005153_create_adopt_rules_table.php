<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdoptRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adopt_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('year')->comment('年限');
            $table->unsignedInteger('price')->comment('价格');
            $table->unsignedInteger('egg_num')->comment('可提鸡蛋数');
            $table->timestamps();
        });

        DB::table('adopt_rules')->insert(['year' => 2,'price' => 0,'egg_num' => 360]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adopt_rules');
    }
}
