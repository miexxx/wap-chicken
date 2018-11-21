<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScopeToAticleInfosToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aticle_infos', function (Blueprint $table) {
            $table->unsignedTinyInteger('scope')->default(1)->index()->comment('文章类别');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_infos', function (Blueprint $table) {
            $table->dropColumn('scope');
        });
    }
}
