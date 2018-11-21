<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAfterSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('after_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content')->nullable()->comment('配送说明');
            $table->timestamps();
        });

        $init = ['content' => ''];
        DB::table('after_sales')->insert($init);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('after_sales');
    }
}
