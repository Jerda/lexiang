<?php
namespace App\Observers;

use App\Model\EnterpriseAdmin;

class EnterpriseAdminObserver
{

    /**
     * 监听企业管理员创建之前的事件
     * @param EnterpriseAdmin $admin
     * @throws \Exception
     */
    public function creating(EnterpriseAdmin $admin)
    {
        $admin->repeat($admin->enterprise_id, $admin->user_id);

        $admin->hasAdmin($admin->enterprise_id);
    }
}
