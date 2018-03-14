<?php
namespace App\Http\Controllers\Api;

use App\Model\Concern;
use App\Model\User;
use Illuminate\Http\Request;
use App\Model\ConcernInvite;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class ConcernController extends BaseController
{
    /**
     * --------------------------------------
     * 用户关注 控制器
     * --------------------------------------
     */


    /**
     * 通过用户获取关注人
     * @param Request $request
     */
    public function getListByUser(Request $request)
    {
        User::where('id', $this->userId())->with('concern')->get();
        /*if (empty($request->input('user_id'))) {
            User::where('id', auth()->guard('api')->user()->id)->with('concern')->get();
        } else {
            User::where('id', $request->input('user_id'))->with('concern')->get();
        }*/
    }


    /**
     * 发送关注请求
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendRequest(Request $request)
    {
        try {
            $this->checkHasConcern($request->input('invitees_user_id'));
            $this->checkHasInvite($request->input('invitees_user_id'));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }

        ConcernInvite::create([
            'user_id' => $this->userId(),
            'invitees_user_id' => $request->input('invitees_user_id'),
            'status' => 0
        ]);

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 同意关注请求
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function agreeRequest(Request $request)
    {
        $concern_invite = ConcernInvite::where('id', $request->input('id'))->first();

        Concern::create([
            'user_id' => $concern_invite->user_id,
            'concern_user_id' => $concern_invite->invitees_user_id
        ]);

        $concern_invite->status = 1;

        $concern_invite->save();

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 拒绝邀请
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refuseRequest(Request $request)
    {
        ConcernInvite::where([
            'invitees_user_id' => $this->userId(),
            'user_id' => $request->input('invitees_user_id'),
            'status' => 0])->update(['status' => 3]);

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 获取邀请列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInviteList()
    {
        $res = ConcernInvite::where(['user_id' => $this->userId(), 'status' => 0])
            ->with(['inviteUser' => function($query) {
                $query->with('wechat');
            }])->get();

        return response()->json(['data' => $res]);
    }


    /**
     * 获取被邀请列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInviteeList()
    {
        $res = ConcernInvite::where(['invitees_user_id' => $this->userId(), 'status' => 0])
            ->with(['user' => function($query) {
                $query->with('wechat');
            }])->get();

        return response()->json(['data' => $res]);
    }


    /**
     * 取消关注
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request)
    {
        Concern::where(['user_id' => $this->userId(), 'concern_user_id' => $request->input('concern_user_id')])
            ->first();

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 检测是否已经关注
     * @param $invitees_user_id
     */
    private function checkHasConcern($invitees_user_id)
    {
        $res = Concern::where(['user_id' => $this->userId(), 'concern_user_id' => $invitees_user_id])->first();

        if (!empty($res)) {
            throw new Exception(trans('system.can_not_add_repeat'));
        }
    }


    /**
     * 检测是否已发送邀请
     * @param $invitees_user_id
     */
    private function checkHasInvite($invitees_user_id)
    {
        $res = ConcernInvite::where(['user_id' => $this->userId(), 'status' => 0, 'invitees_user_id' => $invitees_user_id])->first();

        if (!empty($res)) {
            throw new Exception(trans('system.concern_request_has'));
        }
    }
}
