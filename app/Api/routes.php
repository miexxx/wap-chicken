<?php

Route::group([
    'namespace' => 'App\Api\Controllers',
    'middleware' => ['api']
], function () {
    /// 认证
    Route::get('/home/{userCode?}', 'AuthController@login')->name('login');
    Route::get('/auth/oauth_callback/{userCode?}', 'AuthController@oauth_callback')->name('oauth_callback');
    Route::post('/auth/logout', 'AuthController@logout');
    Route::post('/auth/refresh', 'AuthController@refresh');
    Route::get('/auth/shared/{code}', 'AuthController@shared')->name('shared');
    Route::get('/callback','AuthController@callback')->name('callback');

    /// 首页
    Route::get('/banner','HomeController@banner');

    /// 商品
    Route::get('/items', 'ItemController@index');
    Route::post('/items/search', 'ItemController@search'); //關鍵詞搜索
    Route::get('/items/recommended','ItemController@recommended');
    Route::get('/items/hot','ItemController@hot');
    Route::get('/items/{item}', 'ItemController@show');
    Route::get('/extension/items','ItemController@extensions');
    Route::get('/categories/{type}', 'CategoryController@index');
    Route::get('/categories/{id}/items', 'CategoryController@show');
    Route::get('/items/type/egg','ItemController@egg');


    /// 支付通知

    Route::post('/orders/paid_notify', 'OrderController@paidNotify')->name('wechat.paid_notify');
    Route::post('/orders/refund_notify', 'OrderController@refundNotify')->name('wechat.refund_notify');
    Route::any('/orders/refund_callback', 'RefundController@refund_callback');

    /// JSSDK
    Route::get('/jsSdk','JsSdkController@getSdk');

    ///素材管理
    Route::get('/material/articles','MaterialController@articles');
    Route::get('/material/videos','MaterialController@videos');
    Route::get('/material/article','MaterialController@article');
    Route::get('/material/video','MaterialController@video');

    ///充值金额优惠
    Route::get('/recharge','RechargeController@index');

    //认养年限及金额
    Route::get('/adoptions','AdoptionController@index');

    //认养可提取的鸡蛋数
    Route::get('/adoptions/eggs','AdoptionController@show');

    //委托代卖鸡 卖鸡蛋的利润
    Route::get('/sellsale','SellsaleController@index');

    //运费模版
    Route::get('/freights','FreightController@index');

    //优惠卷列表
    Route::get('/coupons','CouponController@index');

    //分期配送选择接口
    Route::get('/delivery','DeliveryController@index');

    /// 用户登录操作
    Route::group([
        'middleware' => ['auth:api']
    ], function () {
        /// 订单
        Route::get('/orders/status', 'OrderController@orderstatus'); //订单状态
        Route::get('/orders', 'OrderController@index');
        Route::post('/orders', 'OrderController@store');
        Route::delete('/orders/{order}', 'OrderController@destroy');
        Route::put('/orders/{order}/pay', 'OrderController@pay');
        Route::put('/orders/{order}/confirm', 'OrderController@confirm');
        Route::get('/orders/{order}', 'OrderController@show');
        Route::get('/orders/{order}/express', 'OrderController@express');
        Route::post('/orders/statement','OrderController@statement');

        //分期配送
        Route::get('/orders/stage/index','OrderController@stageindex');

        //余额支付
        Route::post('/orders/{order}/walletpay','OrderController@walletpay');

        /// 退款
//        Route::post('/orders/{order}/refund', 'RefundController@store');
//        Route::get('/refunds', 'RefundController@index');
//        Route::get('/refunds/{orderRefund}', 'RefundController@show');
//        Route::put('/refunds/{orderRefund}/cancel', 'RefundController@cancel');


        ///个人信息
        Route::get('users/show','UserController@show');
        Route::post('users/store','UserController@store');
        Route::get('/sharer','UserController@sharer');
        Route::get('/inviteLink','UserController@inviteLink');



        ///系统设置
        Route::get('/contact','ContactController@show');
        Route::get('/introduction','BaseController@introduction');
        Route::get('/operation','BaseController@operation');
        Route::get('/distribution','BaseController@distribution');
        Route::get('/afterSale','BaseController@afterSale');
        Route::get('/customer','BaseController@customer');

        /// 个人购物车
        Route::get('/cats','CatController@index');
        Route::delete('/cats/{item_id}', 'CatController@destroy');
        Route::post('/cats/{item_id}', 'CatController@store');
        Route::put('/cats/edit/{cat}','CatController@update');

        ///个人钱包
        Route::get('/wallet','WalletController@show');
        Route::post('/wallet/cash','WalletController@cash');
        Route::post('/wallet/recharge/{recharge}','WalletController@recharge');

        ///个人充值记录/提现记录
        Route::get('/cash/record','WalletController@cashrecord');
        Route::get('/order/rgrecord','OrderController@rgrecord');


        ///个人优惠卷
        Route::get('/me/coupons','CouponController@show');

        //领取优惠卷
        Route::get('/me/coupons/{coupon}','CouponController@accpet');
        Route::post('/me/coupons/money','CouponController@money');

        //使用兑换卷兑换金额
        Route::post('/me/qrcode','QrcodeController@getmoney');

        /// 个人地址
        Route::get('/orderaddress','AddressController@index');
        Route::post('/addadr','AddressController@store');
        Route::get('/defaultAdd/{address}','AddressController@defaultAdd');

        Route::get('/addresss/{address}','AddressController@show');
        Route::post('/addresss/{address}','AddressController@update');
        Route::delete('/addresss/{address}','AddressController@destroy');

        ///个人仓库
        Route::get('/storehouse/egg','StorehouseController@egg');
        Route::get('/storehouse/chicken','StorehouseController@chicken');
        Route::get('/storehouse/record','StorehouseController@record');
        Route::post('/storehouse/{storehouse}','StorehouseController@distribute');
        Route::post('/storehouse/sn/conver','StorehouseController@conver');
        Route::post('/storehouse/senduser/{storehouse}','StorehouseController@senduser');

        ///个人认养
        Route::get('/support','SupportController@show');
        Route::delete('/support/{support}','SupportController@destory');
        Route::post('/support/apply/{support}','SupportController@apply');
        //提取鸡蛋
        Route::post('/support/{support}/egg','SupportController@putegg');

        /// 个人收藏
        Route::get('/favorites', 'FavoriteController@index');
        Route::delete('/favorites/{item_id}', 'FavoriteController@destroy');
        Route::post('/favorites/{item_id}', 'FavoriteController@store');

        //分享家中心
        Route::get('/sharerCenter','SharerCenterController@show');

        //用户分享福利
        Route::get('/inviteBonus','UserController@checkInviteNum');
        Route::get('/inviteSaleBonus','UserController@checkInviteSale');
        Route::get('/shareGetCoupon','UserController@shareGetCoupon');

    });
});