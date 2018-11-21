<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/13
 * Time: 15:48
 * Function:
 */

namespace App\Admin\Controllers\Convertibility;


use App\Http\Controllers\Controller;

use App\Models\Qrcodes;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
/**
 * @module 兑换卷管理
 *
 * Class CategoryController
 * @package App\Admin\Controllers\Items
 */
class QrcodeController extends Controller
{
    /**
     * @permission 兑换卷列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $qrcodes = (new Qrcodes())->orderBy('created_at','desc')->paginate(10);
        $header = '兑换卷列表';
        return view('admin::conver.qrcode',compact('qrcodes','header'));
    }

    public function destroy(Qrcodes $qrcode){
        $qrcode->delete();
        return response()->json(['status' => 1, 'message' => '成功']);
    }

    public function create($type){

        $time = Qrcodes::TIME;
        while($time--){
            $qrcode = new Qrcodes();
            $sn = md5(date('YmdHis')  . rand(10, 99));
            $path = $sn;
            $password =md5(date('YmdHis')  . rand(100, 200));
            $qrcode->sn = $sn;
            QrCode::format('png')->size(200)->encoding('UTF-8')->generate($sn.' '.$password,public_path('storage/qrcode/'.$sn.'.png'));
            $qrcode->password =$password;
            $qrcode->type = $type;
            $qrcode->qrcode ='qrcode/'.$sn.'.png';
            $qrcode->save();
        }
        return redirect()->route('admin::qrcodes.index');
    }

}