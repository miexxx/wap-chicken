<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSharedAndParentIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('is_shared')->default(0)->index()->comment('是否是分享家0不是123456等级');
            $table->unsignedInteger('parent_id')->nullable()->index()->comment('邀请者');
            $table->timestamp('stt_at')->nullable()->index()->comment('结算时间');
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
            $table->dropColumn('is_shared');
            $table->dropColumn('parent_id');
            $table->dropColumn('pro_money');
            $table->dropColumn('stt_at');
        });
    }
}
