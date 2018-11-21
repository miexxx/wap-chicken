<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Support\Facades\Log;

class ChangeLevel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user =$this->user;
        $userLevel = UserLevel::where('level','>',$user->is_shared)->orderBy('level','asc')->first();
        if($userLevel){
            $money = 0;
            $money += $user->userPromote()->whereHas('order', function ($query) use($user) {
                $query->whereIn('status', [2,4])->whereBetween('delivered_at', [ date('Y-m-d H:i:s', strtotime($user->stt_at.'-22 day')),$user->stt_at]);
            })->sum('money');

            $money += $user->userInvite()->whereHas('order', function ($query) use($user) {
                $query->whereIn('status', [2,4])->whereBetween('delivered_at', [date('Y-m-d H:i:s', strtotime($user->stt_at.'-22 day')),$user->stt_at]);
            })->sum('money');

            $money = $money / 100;

            if($money >= $userLevel->money) {
                $user->is_shared = $userLevel->level;
                $user->stt_at = date('Y-m-d H:i:s',strtotime("$user->stt_at +22 day"));
                $user->save();
                Log::info(sprintf('ChangeLevel Fail:user_id[%s]:[%s]', $money, 'level up already'));
            }
            else {
                Log::error(sprintf('ChangeLevel Fail:user_id[%s]:[%s]', $user->id, 'money is no enouth'));
            }
        }else {
            Log::error(sprintf('ChangeLevel Fail:user_id[%s]:[%s]', $user->id, 'level is top'));
        }
    }
}
