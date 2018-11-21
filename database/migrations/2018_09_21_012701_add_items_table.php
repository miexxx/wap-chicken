<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('items', function (Blueprint $table) {
            $table->longText('parameter')->comment('详细参数');
            $table->longText('packaging')->comment('包装售后');
            $table->unsignedInteger('is_extension')->default(0);
            $table->unsignedInteger('unit_price')->default(0)->comment('套餐单价');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {

            $table->dropColumn('parameter');
            $table->dropColumn('packaging');
            $table->dropColumn('is_extension');
            $table->dropColumn('unit_price');

        });
    }
}
