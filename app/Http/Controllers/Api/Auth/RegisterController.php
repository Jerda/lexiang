<?php
namespace App\Http\Controllers\Api\Auth;

use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class RegisterController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | 用户注册控制器
    |--------------------------------------------------------------------------
    */

    public function showRegisterForm()
    {
        return view('wechat.auth.register');
    }


    /**
     * 注册用户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function register(Request $request)
    {
        try {
            $this->validator($request->all());
        } catch (\Exception $e) {
            return $this->unregistered($e->getMessage());
        }

        $user = $this->create($request->all());

        return $this->registered();
    }


    /**
     * 注册管理员
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function registerAdmin(Request $request)
    {
        try {
            $this->validator($request->all());
        } catch (\Exception $e) {
            return $this->unregistered($e->getMessage());
        }

        $user = $this->createAdmin($request->all());

        return $this->registered();
    }


    /**
     * 验证短信码
     * @param $data
     * @throws \Exception
     */
    private function validateMobileCode(&$data)
    {
        $sms = new SMS();

        if (empty($data['code'])) {
            throw new \Exception(trans('system.mobile_code_required'));
        }

        if (!$sms->checkValidate($data['mobile'], $data['code'])) {
            throw new \Exception(trans('system.mobile_code_error'));
        }

        unset($data['code']);
    }


    private function validator(array $data)
    {
        $validator = Validator::make($data, [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
//            'captcha'  => 'required|captcha'
        ]);

        if ($validator->fails()) {  //用户提交字段验证失败
            throw new \Exception($validator->errors()->first());
        } else {
            //判断用户名是否已存在
            $this->userIsExists($data['username']);
        }
    }


    /**
     * 判断用户是否已经注册
     * @param $username
     * @throws \Exception
     */
    private function userIsExists($username)
    {
        if (User::where('username', $username)->first()) {
            throw new \Exception(trans('system.username_is_exists'));
        }
    }


    /**
     * 创建用户
     * @param array $data
     * @return App\Model\User
     */
    private function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
        ]);
    }


    /**
     * 创建管理员
     * @param array $data
     * @return App\Model\User
     */
    private function createAdmin(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'is_admin' => 1,
        ]);
    }


    /**
     * 注册成功,返回信息
     *
     * @return mixed
     */
    protected function registered()
    {
        return response()->json(['message' => trans('system.register_success')]);
    }


    /**
     * 注册失败，返回信息
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    private function unregistered($message)
    {
        return response()->json(['message' => $message], 422);
    }
}
