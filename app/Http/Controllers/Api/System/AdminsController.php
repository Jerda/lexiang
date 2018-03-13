<?php
namespace App\Http\Controllers\Api\System;

use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class AdminsController extends BaseController
{

    /**
     * 获取所有管理员
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $admins = User::admins()->get();

        $admins->each(function($admin) {
            $admin->roles = $admin->getRoleNames();
        });

        return response()->json(['data' => $admins]);
    }


    /**
     * 添加管理员
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        try {
            $this->createValidator($request->all());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],
                config('response_error.validate'));
        }

        $user = User::create([
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'is_admin' => 1,
        ]);

        $user->assignRole($request->input('role'));

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 修改管理员信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modify(Request $request)
    {
        $data = $request->all();

        try {
            $this->modifyValidator($data);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],
                config('response_error.validate'));
        }

        $admin = User::find($data['user_id']);

        $admin->username = $data['username'];

        $this->modifyPassword($admin, $data);

        $admin->save();

        $admin->syncRoles($data['role']); //修改角色

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 修改密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $admin = auth()->guard('api')->user();
        try {
            $this->resetPasswordValidator($request->all());

            $this->checkPassword($admin, $request->input('password'))
                ->modifyPassword($admin, $request->all());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],
                config('response_error.validate'));
        }

        $admin->save();

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 删除管理员
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        User::destroy($request->input('user_id'));

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 获取管理员以及角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        $admin = User::admins()->where('id', $request->input('user_id'))->first();

        $roles = $admin->getRoleNames();

        return response()->json(['data' => [
            'admin' => $admin,
            'roles' => $roles
        ]]);
    }


    /**
     * 创建验证器
     * @param $data
     * @throws \Exception
     */
    protected function createValidator($data)
    {
        $validator = Validator::make($data, [
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            throw new \Exception(trans('system.must_complete_information'));
        }
    }


    /**
     * 修改验证器
     * @param $data
     * @throws \Exception
     */
    protected function modifyValidator($data)
    {
        $validator = Validator::make($data, [
            'username' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            throw new \Exception(trans('system.must_complete_information'));
        }
    }


    /**
     * 修改密码验证器
     * @param $data
     * @throws \Exception
     */
    protected function resetPasswordValidator($data)
    {
        $validator = Validator::make($data, [
            'password' => 'required',
            'reset_password' => 'required|min:6',
        ], [
            'reset_password.min' => trans('system.password_length_min'),
            'password.required' => trans('system.must_complete_information'),
            'reset_password.required' => trans('system.must_complete_information')
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }


    /**
     * 修改密码
     * @param $admin
     * @param $data
     */
    protected function modifyPassword(&$admin, $data)
    {
        if ($data['reset_password']) { //修改密码
            $admin->password = bcrypt($data['reset_password']);
        }
    }


    protected function checkPassword($admin, $password)
    {
        if (!Hash::check($password, $admin->password)) {
            throw new \Exception(trans('system.password_error'));
        }

        return $this;
    }
}
