<?php

namespace App\Http\Controllers\Api;

use App\Model\Enterprise;
use App\Model\User;
use App\Libraries\UserHelp;
use Illuminate\Http\Request;
use App\Model\EnterpriseUser;
use App\Model\EnterpriseAdmin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    use UserHelp;

    /*
    |--------------------------------------------------------------------------
    | 用户控制器
    |--------------------------------------------------------------------------
    */


    /**
     * 获取当前wechat登录用户信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return response()->json(['data' => $this->getUser()]);
    }


    /**
     * 微信用户注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function registerWechat(Request $request)
    {
        try {
            $this->validatorForWechat($request->all());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }

        $user = User::where('id', auth()->guard('api')->user())->update($request->all());

        return response()->json(['message' => trans('system.register_success')]);
    }


    /**
     * 登出
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        $user = auth()->guard('api')->user();

        $accessToken = $user->token();

        DB::table('oauth_refresh_tokens')->where('access_token_id', $accessToken->id)
            ->update(['revoked' => 1]);

        app('cookie')->forget('refreshToken');

        $accessToken->revoke();

        return response()->json(['message' => trans('system.logout_success')]);
    }


    /**
     * 修改用户状态
     * @return \Illuminate\Http\JsonResponse
     */
    public function modifyStatus()
    {
        $data = request()->all();

        User::where('id', $data['user_id'])->update(['status' => $data['status']]);

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 注册用户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        //注册个人信息
        $data = $request->all();

        try {
            $this->validateRegister($data);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],
                config('response_error.validate'));
        }

        User::where('id', $this->getUser()->id)->update($data);

        return response()->json(['message' => trans('system.register_success')]);
    }


    /**
     * 检测用户是否注册，由于手机号是注册时填写字段，故基于手机号判断
     * @return \Illuminate\Http\JsonResponse 如没有注册，返回错误码403；否则仅返回一个信息
     */
    public function checkIsRegister()
    {
        if (empty($this->user()->mobile)) {
            return response()->json(['message' => trans('system.user_is_not_register')],
                config('response_error.validate'));
        }

        return rescue()->json(['message' => trans('system.ok')]);
    }


    /**
     * 通过手机号获取用户，如果该手机号没有被注册，返回403
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserByMobile(Request $request)
    {
        $user = User::where('mobile', $request->input('mobile'))->get();

        if (empty($user)) {
            return response()->json(['message' => trans('system.user_not_exists')],
                config('response_error.validate'));
        }

        return response()->json(['data' => $user]);
    }


    /**
     * 根据用户获取关注人
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConcernUserList(Request $request)
    {
        /*$user_id = empty($request->input('user_id')) ? auth()->guard('api')->user()->id
            : $request->input('user_id');*/
        $user_id = empty($request->input('user_id')) ? $this->getUser()->id
            : $request->input('user_id');

        $user = User::where('id', $user_id)->with(['concern' => function($query) {
            $query->with(['concernUser' => function($query) {
                $query->with('wechat');
            }]);
        }])->first();

        return response()->json(['data' => $user->concern]);
    }


    /**
     * 获取用户的企业
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEnterprise()
    {
        $res = EnterpriseUser::where('user_id', $this->userId())->first();

        if (!empty($res)) {
            $enterprise = Enterprise::where('id', $res->enterprise_id)->first();
        } else {
            $enterprise = null;
        }

        return response()->json(['data' => $enterprise]);
    }


    public function isAdmin()
    {
        $admin = EnterpriseAdmin::where('user_id', $this->userId())->first();

        return response()->json(['data' => $admin]);
    }


    /**
     * 验证用户注册数据
     * 验证失败抛出错误
     * @param $data 验证数据
     * @throws \Exception
     */
    private function validateRegister($data)
    {
        $validator = Validator::make($data, [
            //数据验证
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }


    /**
     * 获取用户详情
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function detail()
    {
        return response()->json(['data' => $this->user()]);
    }*/


    /**
     * 根据ID获取用户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        $user = User::where('id', $request->input('user_id'))->with('wechat')->first();

        return response()->json(['data' => $user]);
    }


    /**
     * 获取所有用户
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $users = User::normal()->where(formatWhere(['name']))->with('wechat')->whereHas('wechat', function ($query) {
            $query->where(formatWhere(['nickname']));
        })->with('concern')->with('enterprises')->paginate(request()->input('limit'));

        return response()->json(['data' => $users]);
    }


    private function validatorForWechat($data)
    {
        $validator = Validator::make($data, [
        ]);

        if ($validator->fails()) {  //用户提交字段验证失败
            throw new \Exception($validator->errors()->first());
        }
    }
}
