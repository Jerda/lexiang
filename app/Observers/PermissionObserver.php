<?php
namespace App\Observers;

use Facades\App\Model\PermissionGroup;
use App\Model\PermissionName;
use Spatie\Permission\Models\Permission;

class PermissionObserver
{
    /*
     * ---------------------------------------------------
     * 权限观察器
     * ---------------------------------------------------
     */


    public function created(Permission $permission)
    {
        PermissionGroup::permissionAddToGroup($permission->id, request()->input('group_id'));

        $this->permissionSaveName($permission->id, request()->input('cn.name'));
    }


    public function deleted(Permission $permission)
    {
        PermissionGroup::delPermissionFromGroup(request()->input('id'));
    }


    /**
     * 为权限添加中文名称
     * @param $permission_id
     * @param $name
     */
    private function permissionSaveName($permission_id, $name)
    {
        PermissionName::create([
            'permission_id' => $permission_id,
            'name' => $name,
        ]);
    }
}
