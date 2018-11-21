<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInviteBonusAndIvSaleBonusToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('invite_bonus')->default(0)->comment('邀请权益0:未享受 1:已享受');
            $table->unsignedTinyInteger('iv_sale_bonus')->default(0)->comment('邀请消费权益0:未享受 1:已享受');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('invite_bonus');
            $table->dropColumn('iv_sale_bonus');
        });
    }
}
