<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddresssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresss', function (Blueprint $table) {
            $table->string('user_name','20');
            $table->dropColumn('national_code');
            $table->dropColumn('province');
            $table->dropColumn('city');
            $table->dropColumn('county');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
