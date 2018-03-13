<?php

namespace App\Http\Controllers\Api\Wechat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Wechat\WechatAccount;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class WechatConfigController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | 微信接口配置控制器
    |--------------------------------------------------------------------------
    */

    protected $callback_msg = '';

    /**
     * POST设置微信配置
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function set(Request $request)
    {
        if (!$this->validator($request->all())) {
            return $this->sendFailedSetResponse();
        }

        try {
            $this->addOrUpdate($request->all());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }

        return $this->sendSuccessSetResponse();
    }


    public function get()
    {
        $wechat_account = WechatAccount::first();

        return $wechat_account ? response()->json(['data' => $wechat_account->toArray()]) : null;
    }


    /**
     * 检测JS授权文件是否已存在
     */
    public function checkJS()
    {
       $res = file_exists(env('WECHAT_JS_FILE'));

       return response()->json(['data' => ['isExists' => $res]]);
    }


    /**
     * 上传JS接口文件
     */
    public function uploadJS()
    {
        $file = request()->file('wechat_js_file');

        return $file->storeAs('wechat', $file->getClientOriginalName());
    }


    /**
     * 验证用户提交微信接口配置字段
     *
     * @param array $data
     * @return bool
     */
    private function validator(array $data)
    {
        $validator = Validator::make($data, [
            /*'name' => 'required',
            'wechat_id' => 'required',
            'init_wechat_id' => 'required',*/
            'app_id' => 'required',
            'app_secret' => 'required',
//            'api' => 'required',
            'token' => 'required',
            'encoding_aes_key' => 'required'
        ]);

        if ($validator->fails()) {  //用户提交字段验证失败
//            $this->callback_msg = $validator->errors();
            return false;
        } else {
            return true;
        }
    }


    /**
     * 如果第一次设置，将添加配置数据，否则更新配置数据
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    private function addOrUpdate(array $data)
    {
        if (empty($wechat_account = WechatAccount::first())) {
            WechatAccount::create($data);
        } else {
            WechatAccount::where('id', $wechat_account->id)->update($data);
        }
    }


    /**
     * 发送设置失败响应
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function sendFailedSetResponse()
    {
        return response()->json(['message' => trans('system.must_complete_information')], 505);
    }


    /**
     * 发送设置成功响应
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function sendSuccessSetResponse()
    {
        return response()->json(['message' => trans('system.set_success')]);
    }
}
