<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User;
use App\Models\UserPromote;
use App\Models\UserInvite;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class ChangeSharer implements ShouldQueue
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
        $user = $this->user;
        DB::transaction(function () use($user) {
            $user->is_shared = 0;
            $user->shared_at = null;
            $user->stt_at = null;
            $user->save();

            $childrens = $user->childrens;
            if(count($childrens)>0) {
                foreach($childrens as $children) {
                    $children->parent_id = null;
                    $children->save();
                }
            }

            $orders = $user->orders()->where('status',0)->pluck('id')->toArray();

            UserPromote::whereIn('order_id',$orders)->delete();

            UserInvite::whereIn('order_id',$orders)->delete();

        });
    }
}
