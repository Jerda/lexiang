<?php

namespace App\Http\Controllers\Api\Wechat;

use App\Model\User;
use Illuminate\Http\Request;
use App\Model\Wechat\WechatUser;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Kernel\Exceptions\Exception;
use Facades\App\Libraries\Wechat\WechatTool;
use App\Http\Controllers\Api\BaseController;

class WechatUserController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | 微信会员控制器
    |--------------------------------------------------------------------------
    */

    /**
     * 获取微信用户数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request)
    {
        $users = User::where(formatWhere(['name']))->with('wechat')->whereHas('wechat', function ($query) {
            $query->where(formatWhere(['nickname', 'subscribe_time']))->where('openid', '<>', '');
        })->paginate($request->input('limit'));

        return response()->json(['data' => $users]);
    }


    public function getUsersByNickname(Request $request)
    {
        $users = WechatUser::where(formatWhere(['nickname']))->get();

        return response()->json(['data' => $users]);
    }


    /**
     * 同步微信端用户到本地
     * @return \Illuminate\Http\JsonResponse
     */
    public function synchronizeUsers()
    {
        try {
            $this->getAndAddUsers();
        } catch (Exception $e) {
        return $this->sendSystemErrorResponse();
    }

        return response()->json(['message' => trans('system.synchronize_success')]);
    }


    /**
     * 拉黑用户
     * @return \Illuminate\Http\JsonResponse
     */
    public function block()
    {
        $openid = WechatTool::getOpenIDByUserID(request()->input('user_id'));

        WechatTool::blockUser($openid);

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 取消拉黑
     * @return \Illuminate\Http\JsonResponse
     */
    public function unblock()
    {
        $openid = WechatTool::getOpenIDByUserID(request()->input('user_id'));

        WechatTool::unblockUser($openid);

        return response()->json(['message' => trans('system.operate_success')]);
    }


    private function getAndAddUsers($nextOpenId = null)
    {
        $open_ids = WechatTool::getUserList($nextOpenId = null);

        DB::transaction(function () use ($open_ids) {
            foreach ($open_ids['data']['openid'] as $open_id) {
                if (empty(WechatTool::getUserIdByOpenID($open_id))) {
                    WechatTool::addUser($open_id);
                }
            }
        }, 4);

        if (count($open_ids['data']['openid']) === 10000) {
            $this->getAndAddUsers($open_ids['next_openid']);
        }
    }
}
