<?php

namespace App\Providers;

use App\Model\Enterprise;
use App\Model\EnterpriseAdmin;
use App\Libraries\Health\Health;
use App\Model\Operate;
use App\Model\PermissionGroup;
use App\Model\User;
use App\Observers\EnterpriseObserver;
use App\Observers\OperateObserver;
use App\Observers\PermissionGroupObserver;
use App\Observers\PermissionObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\EnterpriseAdminObserver;
use Spatie\Permission\Models\Permission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        EnterpriseAdmin::observe(EnterpriseAdminObserver::class);   //企业管理员观察器
        Enterprise::observe(EnterpriseObserver::class);             //企业观察器
        User::observe(UserObserver::class);                         //用户观察器
        Permission::observe(PermissionObserver::class);             //权限观察器
        PermissionGroup::observe(PermissionGroupObserver::class);   //权限组观察器
        Operate::observe(OperateObserver::class);                   //操作观察器
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Health', function() {
            return new Health();
        });
    }
}
