<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Api\BaseController;
use Facades\App\Libraries\SMS\SMS;

class SMSController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | 短信控制器
    |--------------------------------------------------------------------------
    */

    /**
     * 获取服务器网关规则
     * @return \Illuminate\Http\JsonResponse
     */
    public function ruleByServiceProvider()
    {
        return response()->json(['data' => SMS::rulesForServiceProvider(request()->input('service_provider'))]);
    }


    /**
     * 获取服务商
     * @return \Illuminate\Http\JsonResponse
     * {'aliyun': '阿里云', ....}
     */
    public function serviceProviders()
    {
        return response()->json(['data' => SMS::serviceProviders()]);
    }
}