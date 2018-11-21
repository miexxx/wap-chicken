<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/26
 * Time: 17:01
 * Function:
 */

namespace App\Admin\Controllers\Withdraw;


use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletApply;

/**
 * @module 提现申请列表
 *
 * Class UserController
 * @package App\Api\Controllers\Users
 */
class WithdrawController
{
    /**
     * @permission 申请列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $applys = (new WalletApply())->orderBy('created_at','desc')->paginate(10);

        $header = '提现申请列表';
        return view('admin::wallet.wallet', compact('applys','header'));
    }

    public function destroy(WalletApply $walletApply){
        if($walletApply->state === 0){
            $user = (new User())->where('id','=',$walletApply->user_id)->first();
            $user->wallet->state=Wallet::NORNAL;
            $user->wallet->can_money+=$walletApply->money;
            $user->wallet->save();
        }

        $walletApply->delete();
        return response()->json(['status' => 1, 'message' => '成功']);
    }

    public function success(WalletApply $walletApply){

        if($walletApply->state === 0){
            $user = (new User())->where('id','=',$walletApply->user_id)->first();
            if($walletApply->money <=  $user->wallet->can_money)
            $user->wallet->state=Wallet::NORNAL;
            $user->wallet->save();
            $walletApply->state = 1;
            $walletApply->save();

        }
        return response()->json(['status' => 1, 'message' => '操作成功']);
    }


}