<?php

use Illuminate\Database\Seeder;

class UsersLevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_levels')->insert([
            ['name' => '普通分享家','level' => '1','money' => 0,'sales_percent' => 5,'invite_percent' => 8],
            ['name' => '银牌分享家','level' => '2','money' => 10000,'sales_percent' => 7,'invite_percent' => 8],
            ['name' => '金牌分享家','level' => '3','money' => 50000,'sales_percent' => 10,'invite_percent' => 8],
        ]);
    }
}
