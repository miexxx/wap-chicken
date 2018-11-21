<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/23/023
 * Time: 15:18
 */

namespace App\Api\Controllers;

use App\Policies\AddressPolicy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Http\Resources\AddressResource;

class AddressController extends  Controller{

    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function index(){
        $addresss = (new Address())->where('user_id','=',$this->user->id)->orderby('type','desc')->orderby('created_at','desc')->get();
        return api()->collection($addresss, AddressResource::class);
    }

    public function show(Address $address){

        $this->authorize('show', $address);
        return api()->item($address, AddressResource::class);

    }
    public function destroy(Address $address){
        $this->authorize('destroy', $address);
        $address->delete();
        return response()->json(['state'=>0,'msg'=>'删除成功']);
    }

    public function store(Request $request){
        $data=[
            'postal_code' => $request->get('postal_code'),
            'tel' => $request->get('tel'),
            'detail' => $request->get('detail'),
            'user_name' => $request->get('user_name'),
            'user_id' =>$this->user->id,
            'type'=> $request->get('type'),
        ];
        if($request->get('type')){
            $defaultAdd = $this->user->defaultAdd;
            if($defaultAdd){
                $defaultAdd->type=Address::NORNAL_ADD;
                $defaultAdd->save();
            }
        }
        foreach ($data as $key => $value){
            if($value === null){
                return response()->json(['state'=>1,'msg'=>'提交信息不完整']);
            }
        }
        //return response()->json(['state'=>1,'msg'=>'添加地址成功']);
        (new Address())->create($data);
        return response()->json(['state'=>0,'msg'=>'添加地址成功']);
    }
    public function update(Address $address,Request $request){
        $this->authorize('show', $address);
        $data=[
            'postal_code' => $request->get('postal_code'),
            'tel' => $request->get('tel'),
            'detail' => $request->get('detail'),
            'user_name' => $request->get('user_name'),
            'user_id' =>$this->user->id,
            'type'=> $request->get('type'),
        ];
        if($request->get('type')){
            $defaultAdd = $this->user->defaultAdd;
            if($defaultAdd){
                $defaultAdd->type=Address::NORNAL_ADD;
                $defaultAdd->save();
            }
        }
        foreach ($data as $key => $value){
            if($value === null){
                return response()->json(['state'=>1,'msg'=>'提交信息不完整']);
            }
        }
        //return response()->json(['state'=>1,'msg'=>'添加地址成功']);
       $address->update($data);
        return response()->json(['state'=>0,'msg'=>'修改地址成功']);
    }

    public function defaultAdd(Address $address){
        $this->authorize('show', $address);
        $defaultAdd = $this->user->defaultAdd;
        if($defaultAdd){
            $defaultAdd->type=Address::NORNAL_ADD;
            $defaultAdd->save();
        }
        $address->type=Address::DEFALUT_ADD;
        $address->save();
        return response()->json(['state'=>1,'msg'=>'修改成功']);
    }





}