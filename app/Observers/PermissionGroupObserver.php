<?php
namespace App\Observers;

use App\Model\PermissionGroup;
use Spatie\Permission\Models\Permission;

class PermissionGroupObserver
{
    /*
     * ---------------------------------------------------
     * 权限组观察器
     * ---------------------------------------------------
     */


    public function deleting(PermissionGroup $permissionGroup)
    {
        PermissionGroup::canDel(request()->input('id'));
    }


}
