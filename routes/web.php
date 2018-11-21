<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

\View::addExtension('html', 'php');
Route::get('/app/home', function () {
    return view('index');
})->name('home');
Route::group([
    'middleware' => ['user']
],function(){

    Route::get('/app/moneycoupon',function (){
        return view('app.monencoupon.index');
    });

    Route::get('/app/company',function (){
        return view('app.company.index');
    });

    Route::get('/app/recharge',function (){
        return view('app.recharge.index');
    });

    Route::get('/app/pay',function (){
        return view('app.pay.index');
    });

    Route::get('/app/store',function (){
        return view('app.store.index');
    });

    Route::get('/app/member',function (){
        return view('app.member.index');
    });

    Route::get('/app/companymenu',function (){
        return view('app.companymenu.index');
    });

    Route::get('/app/categoryproduct',function (){
        return view('app.categoryproduct.index');
    });

    Route::get('/app/product/1',function (){
        return view('app.product.1.index');
    });

    Route::get('/app/product/',function (){
        return view('app.product.index');
    });

    Route::get('/app/entrust',function (){
        return view('app.entrust.index');
    });

    Route::get('/app/adopt',function (){
        return view('app.adopt.index');
    });

    Route::get('/app/coupon',function (){
        return view('app.coupon.index');
    });

    Route::get('/app/message',function (){
        return view('app.message.index');
    });

    Route::get('/app/cash',function (){
        return view('app.cash.index');
    });

    Route::get('/app/countsearch',function (){
        return view('app.countsearch.index');
    });

    Route::get('/app/countsearch',function (){
        return view('app.countsearch.index');
    });

    Route::view('/app/moneycoupon', 'app.moneycoupon.index');
    Route::view('/app/objcoupon', 'app.objcoupon.index');
    Route::view('/app/exchange', 'app.exchange.index');
    Route::view('/app/givestore', 'app.givestore.index');
    Route::view('/app/videoList', 'app.videoList.index');
    Route::view('/app/outstore', 'app.outstore.index');
    Route::view('/app/car', 'app.car.index');
    Route::view('/app/eggstore', 'app.eggstore.index');
    Route::view('/app/chickenstore', 'app.chickenstore.index');
    Route::view('/app/bind', 'app.bind.index');
    Route::view('/app/couponlist', 'app.couponlist.index');
    Route::view('/app/addaddress', 'app.addaddress.index');
    Route::view('/app/address', 'app.address.index');
    Route::view('/app/orderdetail', 'app.orderdetail.index');
    Route::view('/app/confirmorder', 'app.confirmorder.index');
    Route::view('/app/orderall', 'app.orderall.index');
    Route::view('/app/submitOrder', 'app.submitOrder.index');
    Route::view('/app/wxallarticle', 'app.wxallarticle.index');
    Route::view('/app/companyprofile', 'app.companyprofile.index');
    Route::view('/app/activeArticle', 'app.activeArticle.index');
    Route::view('/app/noticeArticle', 'app.noticeArticle.index');
    Route::view('/app/share', 'app.share.index');
    Route::view('/app/me', 'app.me.index');
    Route::view('/app/wallet', 'app.wallet.index');

    Route::view('/app/spread', 'app.spread.index');
    Route::view('/app/shareurl', 'app.shareurl.index');
    Route::view('/app/shareadpot', 'app.shareadpot.index');


});