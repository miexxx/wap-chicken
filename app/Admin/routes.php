<?php

Admin::registerAdminRoutes();

Route::group([
    'namespace' => 'App\Admin\Controllers',
    'prefix' => 'admin',
    'middleware' => ['web', 'admin'],
    'as' => 'admin::'
], function () {
    Route::get('/', 'HomeController@index')->name('main');
    Route::post('/upload/image', 'UploadController@image')->name('upload.image');
    Route::post('/upload/cover', 'UploadController@cover')->name('upload.cover');
    Route::delete('/upload/cover', 'UploadController@deleteCover')->name('upload.delete_cover');
    Route::post('/upload_image', 'UploadImageController@uploadImage')->name('upload.upload_image');

    ///
    Route::group([
        'middleware' => ['admin.check_permission']
    ], function () {
        /// 商品管理
        Route::group([
            'namespace' => 'Items'
        ], function () {
            Route::get('/inviteCoupon','CouponController@inviteCoupon')->name('coupons.inviteCoupon');
            Route::get('items/warning', 'ItemController@warning')->name('items.warning');
            Route::put('items/warning', 'ItemController@warning')->name('items.warning.update');
            Route::resource('items', 'ItemController')->except('show');
            Route::resource('categories', 'CategoryController')->except('show');
            Route::resource('categoriest', 'CategorytController')->except('show');
            Route::resource('topics', 'TopicController')->except('show');
            Route::resource('freights', 'FreightController');
            Route::resource('coupons', 'CouponController');
            Route::get('items/check','ItemController@checkSn')->name('items.check');
        });

        /// 订单管理
        Route::group([
            'namespace' => 'Orders'
        ], function () {
            Route::get('/orders', 'OrderController@index')->name('orders.index');
            Route::get('/paying', 'OrderController@paying')->name('orders.paying');
            Route::get('/delivering', 'OrderController@delivering')->name('orders.delivering');
            Route::get('/receiving', 'OrderController@receiving')->name('orders.receiving');
            Route::get('/commenting', 'OrderController@commenting')->name('orders.commenting');
            Route::get('/finish', 'OrderController@finish')->name('orders.finish');
            Route::get('/orders/{order}', 'OrderController@show')->name('orders.show');
            Route::put('/orders/{order}/modify_price', 'OrderController@modifyPrice')->name('orders.modify_price');
            Route::put('/orders/{order}/deliver', 'OrderController@deliver')->name('orders.deliver');
            Route::delete('/orders/{order}', 'OrderController@destroy')->name('orders.destroy');

            ///
            Route::get('/refunds', 'RefundController@index')->name('refunds.index');
            Route::put('/refunds/{order_refund}/agree', 'RefundController@agree')->name('refunds.agree');
            Route::put('/refunds/{order_refund}/refuse', 'RefundController@refuse')->name('refunds.refuse');

            ///
            Route::resource('comments', 'CommentController')->except(['create', 'edit']);
        });

        /// 首页管理
        Route::group([
            'namespace' => 'Home'
        ], function () {
            Route::resource('banners', 'BannerController')->except('show');
            Route::resource('navigations', 'NavigationController')->except('show');
            Route::resource('recommends', 'RecommendController')->only(['index', 'store', 'destroy']);
        });

        /// 用户管理
        Route::group([
            'namespace' => 'Users'
        ], function () {
            Route::resource('users', 'UserController')->only(['index', 'show']);
            Route::get('userLevels/checkName','UserLevelController@checkName')->name('userLevels.checkName');
            Route::get('userLevels/checkLevel','UserLevelController@checkLevel')->name('userLevels.checkLevel');
            Route::resource('userLevels','UserLevelController')->only(['index','edit','update']);
        });

        ///系统设置
        Route::group([
            'namespace' => 'Base'
        ], function () {
            Route::resource('/about', 'AboutController')->only(['index', 'update']);
            Route::resource('/templates','TemplateController')->only(['index']);
            Route::resource('/contacts','ContactController')->only(['index','update']);
            Route::resource('/introductions','IntroductionController')->only(['index','update']);
            Route::resource('/operations','OperationController')->only(['index','update']);
            Route::resource('/distributions','DistributionController')->only(['index','update']);
            Route::resource('/afterSales','AfterSaleController')->only(['index','update']);
            Route::resource('/customers','CustomerController')->only(['index','update']);
            Route::get('/material/articles','MaterialController@article')->name('materials.article');
            Route::get('/material/articles/updateList','MaterialController@updateList')->name('materials.updateList');
            Route::get('/material/videos','MaterialController@video')->name('materials.video');
            Route::get('/material/videos/updateVideos','MaterialController@uploadVideos')->name('materials.uploadVideos');
            Route::put('/material/{aticleInfo}/setScope','MaterialController@setScope')->name('materials.setScope');
            Route::resource('/adoptRules', 'AdoptRuleController')->only(['index', 'update']);
        });


        //分期配送
        Route::group([
            'namespace'=>'Delivery'
        ],function(){
            Route::resource('deliverys','DeliveryController');
        });

        //认养规则制定
        Route::group([
            'namespace'=>'Adoption'
        ],function(){
            //认养年限与价格
            Route::get('/adoptions/index','AdoptionController@index')->name('adoptions.index');
            //认养年限可提现鸡蛋
            Route::get('/adpotions/show','AdoptionController@show')->name('adoptions.show');
        });

        //委托代卖规制制定
        Route::group([
            'namespace' => 'Sellsale'
        ],function(){
            //委托代卖
           Route::get('/sellsale/index','SellsaleController@index')->name('sellsale.index');
           Route::put('/sellsale/update','SellsaleController@update')->name('sellsale.update');

           Route::get('/sellsale/apply','SellsaleController@apply')->name('sellsale.apply');
            Route::get('/sellsale/success/{userSellsale}','SellsaleController@success')->name('sellsale.success');
            Route::delete('/sellsale/apply/{userSellsale}','SellsaleController@destroy')->name('sellsale.delete');
        });

        //批量生成兑换卷
        Route::group([
            'namespace'=>'Convertibility'
        ],function(){
            //生成实物卷
            Route::get('/conver/index','ConverController@index')->name('convers.index');
            Route::delete('/conver/index/{conver}','ConverController@destroy')->name('convers.destroy');
            Route::get('/conver/create/{type}','ConverController@create')->name('convers.create');
            //生成兑换卷
            Route::get('/qrcode/index','QrcodeController@index')->name('qrcodes.index');
            Route::delete('/qrcode/index/{qrcode}','QrcodeController@destroy')->name('qrcodes.destroy');
            Route::get('/qrcode/create/{type}','QrcodeController@create')->name('qrcodes.create');
        });

        //充值金额设定
        Route::group([
            'namespace'=>'Recharge'
        ],function(){
            //添加金额
            Route::get('/recharge/index','RechargeController@index')->name('recharge.index');
            //删除
            Route::delete('/recharge/index/{recharge}','RechargeController@destroy')->name('recharge.destroy');
            Route::get('/recharge/edit/{recharge}','RechargeController@edit')->name('recharge.edit');
            Route::get('/recharge/create','RechargeController@create')->name('recharge.create');
            Route::post('/recharge/store','RechargeController@store')->name('recharge.store');
            //修改金额
            Route::post('/recharge/update/{recharge}','RechargeController@update')->name('recharge.update');
        });

        //申请提现列表
        Route::group([
            'namespace'=>'Withdraw'
        ],function(){
            //申请提现列表
            Route::get('/withdraw/index','WithdrawController@index')->name('withdraw.index');
            Route::delete('/withdraw/index/{walletApply}','WithdrawController@destroy')->name('withdraw.destroy');
            Route::get('/withdraw/success/{walletApply}','WithdrawController@success')->name('withdraw.success');
        });

    });
});