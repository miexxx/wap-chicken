<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address')->nullable()->comment('地址');
            $table->string('phone')->nullable()->comment('联系电话');
            $table->string('email')->nullable()->comment('电子邮箱');
            $table->string('wechat_no')->nullable()->comment('微信号');
            $table->string('code_img')->nullable()->comment('二维码');
            $table->timestamps();
        });
        $init = ['address' => '','phone' => '','email' => '','wechat_no' => '','code_img' => ''];

        DB::table('contacts')->insert($init);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
