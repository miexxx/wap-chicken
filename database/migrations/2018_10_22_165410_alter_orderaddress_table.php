<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderaddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //order_addresses    $table->unsignedInteger('type');
        Schema::table('order_addresses', function (Blueprint $table) {
            $table->dropColumn('national_code');
            $table->dropColumn('province');
            $table->dropColumn('city');
            $table->dropColumn('county');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('type');
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
