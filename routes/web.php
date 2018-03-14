<?php
/*
|--------------------------------------------------------------------------
| 获取验证码
|--------------------------------------------------------------------------
*/
Route::post('api/captcha/{config?}', function(\Mews\Captcha\Captcha $captcha, $config = 'default') {
    return $captcha->src($config);
});
/*
|--------------------------------------------------------------------------
| 微信主服务
|--------------------------------------------------------------------------
*/
Route::any('wechat/serve', 'Api\Wechat\WechatController@actionServer');
/*
|--------------------------------------------------------------------------
| 微信网页授权回调
|--------------------------------------------------------------------------
*/
Route::post('api/wechat/getOauthURL', 'Api\Wechat\WechatController@getOauthURL');

Route::get('wechat/oauth_callback', 'Api\Wechat\WechatController@oauthCallback');
/*
|--------------------------------------------------------------------------
| 微信JS验证文件
|--------------------------------------------------------------------------
*/
Route::any('/MP_verify_tBqhBHT2Oqs3xYcT.txt', 'Api\Wechat\WechatController@returnJSFile');

Route::get('{all}', 'RouterController@index')->where(['all' => '.*']);

/*
|--------------------------------------------------------------------------
| 短信验证
|--------------------------------------------------------------------------
*/
Route::post('/sms/validate', 'Api\SMSController@getValidate');


/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
Route::group(['namespace' => 'Api', 'prefix' => 'api'], function() {
    //前端页面权限
    Route::post('/frontend_permission', 'Auth\PermissionController@auth')->middleware('auth:api');
    /**
     * 用户身份验证
     */
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function() {
        //登录
        Route::post('login', 'LoginController@login');
        //注册管理员
        Route::post('/registerAdmin', 'RegisterController@registerAdmin');
        //刷新access_token
        Route::post('/refreshToken', 'LoginController@refreshToken');


    });
    Route::group(['middleware' => 'auth:api'], function() {
        /**
         * 用户数据路由
         */
        Route::group(['prefix' => 'user'], function() {
            //注册
            Route::post('/registerWechat', 'UserController@registerWechat');
            Route::post('/modify', 'UserController@modify');
            //获取用户信息
            Route::post('info', 'UserController@user');
            Route::post('all', 'UserController@all')->middleware(['permission']);
            //退出登录
            Route::post('logout', 'UserController@logout');
            Route::post('get', 'UserController@get')->middleware(['permission']);
            Route::post('detail', 'UserController@detail')->middleware(['permission']);
            Route::post('modifyStatus', 'UserController@modifyStatus')->middleware(['permission']);
            Route::post('getConcernUserList', 'UserController@getConcernUserList')->middleware(); //获取用户关注人
            Route::post('getUserByMobile', 'UserController@getUserByMobile')->middleware(); //获取用户关注人
            Route::post('getEnterprise', 'UserController@getEnterprise');
            Route::post('isAdmin', 'UserController@isAdmin');
            Route::post('applyJoinEnterprise', 'UserController@applyJoinEnterprise');
        });
        /**
         * Wechat
         */
        Route::group(['prefix' => 'wechat', 'namespace' => 'Wechat'], function() {
            /**
             * 微信用户
             */
            Route::group(['prefix' => 'user'], function() {
                Route::post('getWechatUsers', 'WechatUserController@getUsers');
                Route::post('getUsersByNickname', 'WechatUserController@getUsersByNickname');
                Route::post('synchronizeUsers', 'WechatUserController@synchronizeUsers');
            });
            /**
             * 微信配置
             */
            Route::group(['prefix' => 'config'], function() {
                Route::post('get', 'WechatConfigController@get');
                Route::post('set', 'WechatConfigController@set');
                Route::any('uploadJS', 'WechatConfigController@uploadJS');
                Route::post('checkJS', 'WechatConfigController@checkJS');
            });
            /**
             * 微信按钮
             */
            Route::group(['prefix' => 'menu'], function () {
                Route::post('getMenus', 'WechatMenuController@getMenus');
                Route::post('getLevelOnes', 'WechatMenuController@getLevelOnes');
                Route::post('add', 'WechatMenuController@actionAdd');
                Route::post('modify', 'WechatMenuController@actionModify');
                Route::post('modifySortId', 'WechatMenuController@actionModifySortId');
                Route::post('del', 'WechatMenuController@actionDel');
                Route::post('issueMenus', 'WechatMenuController@issueMenus');
            });
            /**
             * 素材管理
             */
            Route::group(['prefix' => 'material'], function () {
                Route::post('news', 'WechatMaterialController@news');
            });
            /**
             * 客服管理
             */
            Route::group(['prefix' => 'service'], function() {
                Route::post('add', 'WechatServiceController@add');
                Route::post('services', 'WechatServiceController@services');
            });
        });
        /**
         * 系统设置
         */
        Route::group(['prefix' => 'system', 'namespace' => 'System'], function() {
            Route::post('getServiceProviders', 'SMSController@serviceProviders');
            Route::post('getRuleByServiceProvider', 'SMSController@ruleByServiceProvider');
            /**
             * 角色管理
             */
            Route::group(['prefix' => 'power', 'namespace' => 'Power'], function() {
                Route::post('addRole', 'RoleController@add');   //添加角色
                Route::post('modifyRole', 'RoleController@modify');   //添加角色
                Route::post('delRole', 'RoleController@del');   //添加角色
                Route::post('getRole', 'RoleController@get');   //获取角色列表
                Route::post('getRoles', 'RoleController@roles');   //获取角色列表
                Route::post('getHasPermission', 'RoleController@getHasPermission');   //获取角色列表
                Route::post('givePermissionToRole', 'RoleController@givePermissionTo'); //给角色分配权限

                Route::post('addPermission', 'PermissionController@add');  //添加权限
                Route::post('delPermission', 'PermissionController@del');  //删除权限
                Route::post('getPermissions', 'PermissionController@permissions'); //获取权限列表
                Route::post('addPermissionGroup', 'PermissionController@addGroup'); //添加权限分组
                Route::post('delPermissionGroup', 'PermissionController@delGroup'); //删除权限分组
                Route::post('modifyPermissionGroup', 'PermissionController@modifyGroup'); //修改权限分组
                Route::post('getPermissionGroups', 'PermissionController@groups'); //获取权限分组
                Route::post('getGroupByPermissionID', 'PermissionController@getGroupByPermissionID'); //根据权限ID获取权限分组id
            });
            Route::group(['prefix' => 'admins'], function() {
                Route::post('getAll', 'AdminsController@getAll');
                Route::post('add', 'AdminsController@add');
                Route::post('modify', 'AdminsController@modify');
                Route::post('delete', 'AdminsController@delete');
                Route::post('get', 'AdminsController@get');
                Route::post('resetPassword', 'AdminsController@resetPassword');       //重置密码
            });
        });
        /**
         * 企业
         */
        Route::group(['prefix' => 'enterprise'], function() {
            Route::post('getEnterpriseType', 'EnterpriseController@getEnterpriseType');
            Route::post('get', 'EnterpriseController@get');
            Route::post('normal', 'EnterpriseController@normal');
            Route::post('waitExamine', 'EnterpriseController@waitExamine');
            Route::post('reject', 'EnterpriseController@reject');
            Route::post('rejectList', 'EnterpriseController@rejectList');
            Route::post('agree', 'EnterpriseController@agree');
            Route::post('register', 'EnterpriseController@register');
            Route::post('modifyStatus', 'EnterpriseController@status');
            Route::post('agreeWorkerJoin', 'EnterpriseController@agreeWorkerJoin');
            Route::post('removeWorker', 'EnterpriseController@removeWorker');
            Route::post('getByName', 'EnterpriseController@getByName');
        });
        /**
         * 企业管理员
         */
        Route::group(['prefix' => 'enterprise_admin'], function() {
            Route::post('getAll', 'EnterpriseAdminController@getAll');
            Route::post('del', 'EnterpriseAdminController@del');
            Route::post('add', 'EnterpriseAdminController@add');
            Route::post('getEnterpriseByAdmin', 'EnterpriseAdminController@getEnterpriseByAdmin');
        });
        /**
         * 员工
         */
        Route::group(['prefix' => 'worker'], function() {
            Route::post('all', 'WorkerController@all');
            Route::post('getByName', 'WorkerController@getByName');
            Route::post('getByMobile', 'WorkerController@getByMobile');
            Route::post('reject', 'WorkerController@reject');
            Route::post('rejectList', 'WorkerController@rejectList');
            Route::post('getWaitExamineWorker', 'WorkerController@getWaitExamineWorker');
        });
        /**
         * 健康
         */
        Route::group(['prefix' => 'health'], function() {
            Route::post('fields', 'HealthController@getFields');
            Route::post('userList', 'HealthController@getListByUserID');
            Route::post('getDetail', 'HealthController@getDetail');
        });
        /**
         * 关注
         */
        Route::group(['prefix' => 'concern'], function() {
            Route::post('sendRequest', 'ConcernController@sendRequest');
            Route::post('getInviteeList', 'ConcernController@getInviteeList');
            Route::post('agreeRequest', 'ConcernController@agreeRequest');
            Route::post('cancel', 'ConcernController@cancel');
        });
    });
});
