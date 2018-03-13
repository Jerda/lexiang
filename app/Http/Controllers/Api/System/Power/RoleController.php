<?php
namespace App\Http\Controllers\Api\System\Power;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Api\BaseController;

class RoleController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | 权限 角色控制器
    |--------------------------------------------------------------------------
    */

    /**
     * 添加角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        Role::create(['name' => $request->input('name')]);

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 修改角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modify(Request $request)
    {
        $role = Role::find($request->input('id'));

        $role->name = $request->input('name');

        $role->save();

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 删除角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function del(Request $request)
    {
        Role::destroy($request->input('id'));

        return response()->json(['message' => trans('system.operate_success')]);
    }


    public function get(Request $request)
    {
        $role = Role::find($request->input('role_id'));

        return response()->json(['data' => $role]);
    }


    public function roles()
    {
        $roles = Role::get();

        return response()->json(['data' => $roles]);
    }


    /**
     * 给角色分配权限
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function givePermissionTo(Request $request)
    {
        $role = Role::find($request->input('role_id'));

        $role->syncPermissions($request->input('permissions'));

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 获取角色拥有的权限
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHasPermission(Request $request)
    {
        $role = Role::find($request->input('role_id'));

        $permissions = $role->permissions()->get();

        return response()->json(['data' => $permissions]);
    }
}
