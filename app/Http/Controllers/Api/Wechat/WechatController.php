<?php

namespace App\Http\Controllers\Api\Wechat;

use App\Http\Controllers\Api\BaseController;

class WeChatController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | 微信接口配置控制器
    |--------------------------------------------------------------------------
    | 微信主控制器，微信端验证配置及服务都在此控制器中
    |
    */


    /**
     * 微信主服务
     * @return mixed
     */
    public function actionServer()
    {
        return app('WechatTool')->server();
    }


    /**
     * 获取网页授权URL地址
     * @return mixed
     */
    public function getOauthURL()
    {
        return app('WechatTool')->getOauthURL();
    }


    /**
     * 微信网页授权回调
     */
    public function oauthCallback()
    {
        $user = app('WechatTool')->oauthCallback();

        //跳转前端一个指定登录路由
        return response('')->header('location', 'http://localhost:8080/wechat.html#/username/'.$user->username);
    }


    /**
     * 返回JS接口授权文件
     * 用户公众号配置
     */
    public function returnJSFile()
    {
        $file_name = env('WECHAT_JS_FILE');

        $handle = fopen($file_name, 'r');

        $content = fread($handle, filesize($file_name));

        return $content;
    }

}
