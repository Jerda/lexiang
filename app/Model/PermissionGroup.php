<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class PermissionGroup extends Model
{
    protected $table = 'permission_group';

    protected $fillable = ['name', 'permission_ids'];


    /**
     * 获取所有权限
     * @return array
     */
    public static function allPermissions()
    {
        $data = [];

        $groups = self::all();

        foreach ($groups as $group) {
            $arr = explode(',', $group['permission_ids']);

            $permissions = Permission::whereIn('id', $arr)->with('cn')->get();

            $data[] = [
                'id' => $group['id'],
                'name' => $group['name'],
                'data' => $permissions->toArray(),
            ];
        }

        return $data;
    }


    /**
     * 检测权限分组中是否还存在权限
     * @param $id
     * @throws \Exception
     */
    public static function canDel($id)
    {
        PermissionGroup::where('id', $id)->first();

        throw new \Exception(trans('system.please_del_child'));
    }


    /**
     * 删除权限组中对应的权限
     * @param $permission_id
     */
    public function delPermissionFromGroup($permission_id)
    {
        $group_id = $this->getGroupBy($permission_id);

        $group = PermissionGroup::find($group_id);

        $arr = explode(',', $group->permission_ids);

        foreach ($arr as $key => $item) {
            if ($item == $permission_id) {
                unset($arr[$key]);
            }
        }

        $group->permission_ids = implode(',', $arr);

        $group->save();
    }


    /**
     * 根据权限获取权限组
     * @param $permission_id
     * @return mixed
     */
    public function getGroupBy($permission_id)
    {
        $groups = $this->all();

        foreach ($groups as $group) {
            $arr = explode(',', $group->permission_ids);

            if (in_array($permission_id, $arr)) {
                return $group->id;
            }
        }
    }


    /**
     * 将权限添加入权限分组
     * @param $permission_id
     * @param $group_id
     */
    public function permissionAddToGroup($permission_id, $group_id)
    {
        $permission = $this->find($group_id);

        if (empty($permission['permission_ids'])) {
            $permission_ids = $permission_id;
        } else {
            $permission_ids = $permission['permission_ids'].','.$permission_id;
        }

        $this->where('id', $group_id)->update(['permission_ids' => $permission_ids]);
    }
}
