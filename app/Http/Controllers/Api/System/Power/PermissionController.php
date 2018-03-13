<?php

namespace App\Http\Controllers\Api\System\Power;

use Illuminate\Http\Request;
use App\Model\PermissionGroup;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class PermissionController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | 权限 角色控制器
    |--------------------------------------------------------------------------
    */


    /**
     * 添加权限
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

        DB::transaction(function() use ($request) {
            Permission::create(['name' => $request->input('name')]);
        }, 2);

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 删除权限
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function del(Request $request)
    {
        DB::transaction(function() use ($request) {
            Permission::destroy($request->input('id'));
        }, 2);

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 获取所有权限
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissions()
    {
        $permissions = PermissionGroup::allPermissions();

        return response()->json(['data' => $permissions]);
    }


    /**
     * 添加权限分组
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addGroup(Request $request)
    {
        if (!$request->input('name')) {
            return response()->json(['message' => trans('system.must_complete_information')],
                config('response_error.validate'));
        }

        PermissionGroup::create(['name' => $request->input('name')]);

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 删除指定分组
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delGroup(Request $request)
    {
        try {
            PermissionGroup::destroy($request->input('id'));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],
                config('response_error.validate'));
        }

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 修改指定分组名称
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modifyGroup(Request $request)
    {
        PermissionGroup::where('id', $request->input('id'))->update(['name' => $request->input('name')]);

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 获取所有权限分组
     * @return \Illuminate\Http\JsonResponse
     */
    public function groups()
    {
        $permission_groups = PermissionGroup::get();

        return response()->json(['data' => $permission_groups]);
    }


    /**
     * 根据权限ID获取权限分组id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroupByPermissionID(Request $request)
    {
        $group_id = $this->getGroupBy($request->input('permission_id'));

        return response()->json(['data' => $group_id]);
    }


    /**
     * 根据权限获取权限组
     * @param $permission_id
     * @return mixed
     */
    private function getGroupBy($permission_id)
    {
        $groups = PermissionGroup::all();

        foreach ($groups as $group) {
            $arr = explode(',', $group->permission_ids);

            if (in_array($permission_id, $arr)) {
                return $group->id;
            }
        }
    }


    /**
     * 创建验证器
     * @param $data
     * @throws \Exception
     */
    private function createValidator($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
            'group_id' => 'required',
        ]);

        if ($validator->fails()) {
            throw new \Exception(trans('system.must_complete_information'));
        }
    }
}
