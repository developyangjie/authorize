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


Route::namespace('Authorize')->group(function () {
    Route::get('/','AuthorizeController@redirectBack');

    Route::middleware(['CheckSign'])->group(function () {
        //提交授权请求
        Route::post('/authorize/create', 'AuthorizeController@create')->name('authorizeCreate');
        //获取全局共享getAccessToken
        Route::post('/authorize/getAccessToken', 'AuthorizeController@getAccessToken')->name('getAccessToken');
        //获取全局共享getJsApiTicket
        Route::post('/authorize/getJsApiTicket', 'AuthorizeController@getJsApiTicket')->name('getJsApiTicket');

    });

    //重定向路由
    Route::get('/authorize/redirect/{state}', 'AuthorizeController@redirect')->name('authorizeRedirect')->where(['state'=>'[0-9A-Za-z]{100}+']);
});

Route::namespace('Wx')->group(function () {
    Route::middleware(['CheckSign'])->group(function () {
        //添加授权公众号
        Route::post('/wx/setWx', 'WxController@setWx')->name('setWx');
        //获取授权微信公众号资料
        Route::post('/wx/getWx', 'WxController@getWx')->name('getWx');
        //发送微信模板消息
        Route::post('/msgWxSend', 'WxController@msgWxSend')->name('msgWxSend');

    });

    //微信消息监听
    Route::any('/wxListen/{app_id}', 'WxListenController@listen')->name('wxListen');
    //授权事件监听
    Route::any('/wxEventListen/{app_id}', 'WxListenController@eventListen')->name('wxEventListen');
    //获取预授权码
    Route::get('/getPreAuthCode', 'WxController@getPreAuthCode')->name('getPreAuthCode');
    //授权回调页
    Route::get('/getAuthCode', 'WxListenController@getAuthCode')->name('getAuthCode');
});

Route::namespace('Msg')->group(function () {
    Route::middleware(['CheckSign'])->group(function () {
        Route::post('/getMsgState', 'MsgController@getMsgState')->name('getMsgState'); //获取消息送达状态
    });
});

Route::namespace('Sms')->group(function () {
    Route::middleware(['CheckSign'])->group(function () {
        Route::post('/smsSend', 'SmsController@smsSend')->name('smsSend'); //发送短信
    });
    Route::get('/smsListen/ly', 'SmsController@smsListenLy')->name('smsListenLy'); //郎宇短信监听
});

Route::namespace('User')->group(function () {
    //登录页面
    Route::get('/login','UserController@login')->name('login');
    //登录方法
    Route::post('/loginPost','UserController@loginPost')->name('loginPost');
    Route::middleware(['AdminLogin'])->group(function () {
        Route::get('/loginOut','UserController@loginOut')->name('loginOut');
        Route::get('/updatePwd','UserController@updatePwd')->name('updatePwd');
        Route::post('/updatePwdPost','UserController@updatePwdPost')->name('updatePwdPost');
    });

});
Route::namespace('Index')->group(function (){
    //首页
    Route::middleware(['AdminLogin'])->group(function () {
        Route::get('/index', 'IndexController@index')->name('adminIndex');
        //授权页面
        Route::get('/authView', 'IndexController@authView')->name('authView');
        Route::middleware(['AuthLogin'])->group(function () {
            //授权公众号首页
            Route::get('/authIndex', 'IndexController@authIndex')->name('authIndex');
            //获取历史消息
            Route::post('/historyListPost', 'IndexController@historyListPost')->name('historyListPost');
            //自定义菜单页面
            Route::get('/getMenuList', 'IndexController@getMenuList')->name('getMenuList');
            //获取自定义菜单数据
//            Route::post('/getMenuListPost', 'IndexController@getMenuListPost')->name('getMenuListPost');
            //创建自定义菜单列表
            Route::post('/createMenuList', 'IndexController@createMenuList')->name('createMenuList');
            Route::post('/addKeyText', 'IndexController@addKeyText')->name('addKeyText');
            //回复列表
            Route::get('/replyIndex', 'ReplyController@index')->name('replyIndex');
            Route::post('/replyListPost', 'ReplyController@replyListPost')->name('replyListPost');
            //删除回复
            Route::post('/replyDel', 'ReplyController@delReply')->name('replyDel');
            //添加回复
            Route::get('/replyAdd', 'ReplyController@add')->name('replyAdd');
            Route::get('/replyEdit', 'ReplyController@edit')->name('replyEdit');
            Route::post('/replyAddPost', 'ReplyController@addPost')->name('replyAddPost');
            //添加文字素材
            Route::post('/addTextMaterialPost', 'ReplyController@addTextMaterialPost')->name('addTextMaterialPost');
            //编辑文字素材
            Route::post('/editTextMaterialPost', 'ReplyController@editTextMaterialPost')->name('editTextMaterialPost');
            //获取所有文字历史消息
            Route::post('/getAllReply', 'ReplyController@getAllReply')->name('getAllReply');
            //添加图文素材
            Route::post('/addNewsMaterialPost', 'ReplyController@addNewsMaterialPost')->name('addNewsMaterialPost');
            //获取某一条回复的详情
            Route::post('/getReplyDetail', 'ReplyController@getReplyDetail')->name('getReplyDetail');
            //更新图文素材
            Route::post('/editNewsMaterialPost', 'ReplyController@editNewsMaterialPost')->name('editNewsMaterialPost');
        });
    });
});
Route::namespace('Manage')->group(function (){
    Route::middleware(['AdminLogin','AuthLogin'])->group(function () {
        //图文素材
        Route::get('/materialIndex/{page?}', 'MaterialController@newsIndex')->name('materialIndex');
        Route::post('/materialDel', 'MaterialController@delMaterial')->name('materialDel');
        Route::post('/newsListPost', 'MaterialController@newsListPost')->name('newsListPost');
        Route::get('/newsAdd', 'MaterialController@newsAdd')->name('newsAdd');
        Route::post('/newsAddPost', 'MaterialController@newsAddPost')->name('newsAddPost');
        Route::get('/editNews', 'MaterialController@editNews')->name('editNews');
        Route::post('/editNewsPost', 'MaterialController@editNewsPost')->name('editNewsPost');
        //群发消息
        Route::post('/sendAllNews', 'MaterialController@sendAllNews')->name('sendAllNews');
        Route::post('/timeSendNews', 'MaterialController@timeSendNews')->name('timeSendNews');
        Route::post('/hadTaskNews', 'MaterialController@hadTaskNews')->name('hadTaskNews');
        //图片素材
        Route::get('/imageIndex', 'MaterialController@imageIndex')->name('imageIndex');
        Route::post('/imageAddPost', 'MaterialController@imageAddPost')->name('imageAddPost');
        Route::post('/imageListPost', 'MaterialController@imageListPost')->name('imageListPost');
        //保存裁剪图片
        Route::post('/saveCropImg', 'MaterialController@saveCropImg')->name('saveCropImg');
        //获取下载素材路径
        Route::post('/downImgFile', 'MaterialController@downImgFile')->name('downImgFile');
        //发送微信预览
        Route::post('/previewNews', 'MaterialController@previewNews')->name('previewNews');
    });
//    Route::get('/newsAdd', 'MaterialController@newsAdd')->name('newsAdd');
});
Route::namespace('Setting')->group(function (){
    Route::middleware(['AdminLogin','AuthLogin'])->group(function () {
        //公众号设置
        Route::get('/appInfo', 'IndexController@appInfo')->name('appInfo');
        Route::get('/flashUserInfo', 'IndexController@flashUserInfo')->name('flashUserInfo');
        Route::post('/intoExpireIn', 'IndexController@intoExpireIn')->name('intoExpireIn');
    });
});



