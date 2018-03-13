<?php

namespace App\Http\Controllers\Api\Wechat;

use Facades\App\Libraries\Wechat\WechatTool;

class WechatServiceController extends WeChatController
{
    /*
    |--------------------------------------------------------------------------
    | 微信客服控制器
    |--------------------------------------------------------------------------
    */


    public function add()
    {
        $res = WechatTool::addService(request()->input('account'), request()->input('nickname'));
        return $res;
    }


    public function services()
    {
        $res = WechatTool::listForService();

        return response()->json(['data' => $res['kf_list']]);
    }
}