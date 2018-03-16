<?php

namespace App\Http\Controllers\Api\System;

use App\Libraries\SMS\SMS;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;

class SMSController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | 短信控制器
    |--------------------------------------------------------------------------
    */
    public $sms;

    public $message = [
        'code' => '您的验证码为：'
    ];


    public function __construct()
    {
        $this->sms = new SMS();
    }


    /**
     * 发送验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSMSCode(Request $request)
    {
        $code = $this->makeCode();

        $res = json_decode($this->sms->send($request->input('mobile'), $this->message['code'].$code));

        if ($res->code == 0) {
            return response()->json(['data' => $code]);
        } else {
            return response()->json(['message' => $res->error], config('response_error.error'));
        }
    }


    /**
     * 验证码
     * @return int
     */
    private function makeCode()
    {
        $code = rand(1000, 9999);

        return $code;
    }
}
