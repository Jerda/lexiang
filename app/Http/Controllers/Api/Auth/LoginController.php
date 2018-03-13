<?php

namespace App\Http\Controllers\Api\Auth;

use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Proxy\TokenProxy;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    /**
     * 登录
     * @param Request $request
     * @param TokenProxy $tokenProxy
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request, TokenProxy $tokenProxy)
    {
        try {
            $this->validateLogin($request->all());
        } catch (\Exception $e) {
            return $this->sendFailedLoginResponse($e->getMessage());
        }

        return $tokenProxy->login($request->input('username'), $request->input('password'));
    }


    /**
     * 登出
     * @param TokenProxy $tokenProxy
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(TokenProxy $tokenProxy)
    {
        return $tokenProxy->logout();
    }


    /**
     * 刷新access token
     * @param TokenProxy $tokenProxy
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(TokenProxy $tokenProxy)
    {
        return $tokenProxy->refresh();
    }


    /**
     * 验证用户登录
     * @param $data
     * @return bool
     * @throws \Exception
     */
    protected function validateLogin($data)
    {
        $validator = Validator::make($data, [
            'username' => 'required|string|max:255|min:5',
            'password' => 'required|string|min:6',
//            'captcha'  => 'required|captcha'
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        } else {
            $this->validateUser($data['username'], $data['password']);

            return true;
        }
    }


    protected function sendFailedLoginResponse($message)
    {
        return response()->json(['message' => $message], config('response_error.validate'));
    }


    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return response()->json(['status' => 0,
            'message' => [$this->username() => [Lang::get('auth.throttle', ['seconds' => $seconds])]]]);
    }


    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        $this->guard()->user();

        return response()->json(['status' => 1, 'message' => trans('system.login_success')]);
    }


    /**
     * 验证用户username & password
     * @param $username
     * @param $password
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     * @throws \Exception
     */
    protected function validateUser($username, $password)
    {
        if (!$user = User::where('username', $username)->first()) {
            throw new \Exception(trans('system.user_not_exists'));
        }


        if (!Hash::check($password, $user->password)) {
            throw new \Exception(trans('system.password_error'));
        }
    }
}
