<?php
namespace App\Http\Controllers\Api;

use App\Libraries\AdminAuth;
use App\Http\Controllers\RouterController;

class BaseController extends RouterController
{

    /**
     * 获取当前用户
     * @return mixed
     */
    /*protected function user()
    {
        return auth()->guard('api')->user();
    }*/

    /**
     * 发送系统错误response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendSystemErrorResponse()
    {
        //todo 加入日志
        return response()->json(['message' => trans('system.error')], 403);
    }


    /**
     * 发送没有权限response
     */
    protected function sendSystemNoAuthResponse()
    {
        //todo 加入日志
        return response()->json(['message' => trans('system.no_author')], 422);
    }


    protected function checkMobile($mobile)
    {
        if (!preg_match_all("/^1[34578]\d{9}$/", $mobile)) {
            throw new \Exception(trans('system.mobile_error'));
        }
    }


    protected function userId()
    {
        return empty(request()->input('user_id')) ? $this->getUser()->id
            : request()->input('user_id');
    }
}
