<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Jobs\ChangeLevel;
use App\Jobs\ChangeSharer;

class ChangeUserLevel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:userLevel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '变更等级命令';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = date('Y-m-d H:i:s',strtotime(" -3 month"));
        $between = [$date,date('Y-m-d H:i:s')];
        $sharers = User::where('is_shared','<>',0)->whereDate('shared_at','<',$date)->whereDoesntHave('orders', function ($query) use ($between){
            $query->whereIn('status',[2,4])->whereBetween('delivered_at',$between);
        })->get();
        foreach($sharers as $sharer) {
            dispatch(new ChangeSharer($sharer));
            $this->info("id:{$sharer->id}|nickname:{$sharer->nickname}");
        }
        $this->info("以上用户失去分享家资格");

        $users = User::where('is_shared','<>',0)->whereDate('stt_at','<',date('Y-m-d h:i:s',time()))->get();
        foreach($users as $user) {
            dispatch(new ChangeLevel($user));
            $this->info("id:{$user->id}|nickname:{$user->nickname}");
        }
    }
}
