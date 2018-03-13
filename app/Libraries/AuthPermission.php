<?php
namespace App\Libraries;

use App\Model\User;

class AuthPermission
{
    /*
    |--------------------------------------------------------------------------
    | 权限控制器
    |--------------------------------------------------------------------------
    */

    protected $exclude = [];

    protected $guard = 'api';

    protected $path_except = [  //排除url中的字段
        'api'
    ];

    protected $frontend_sign = 'frontend_permission';   //前端url标识

    protected $super_admin_name = [     //超级管理员username
        'admin'
    ];


    /**
     * 权限验证
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function auth()
    {
        if ($this->isSuperAdmin(auth()->guard($this->guard)->user())) {
            return true;
        }

        $paths = $this->resolveUrl();

        if ($this->isFrontendAuth($paths)) {
            return $this->authFrontend(auth()->guard($this->guard)->user()); //前端页面跳转以及显示权限
        } else {
            return $this->authByRouter(auth()->guard($this->guard)->user(), $paths); //请求后端权限验证
        }
    }


    /**
     * 根据路由验证权限
     * @param $user
     * @param array $paths
     * @return bool
     */
    private function authByRouter(User $user, array $paths)
    {
        try {
            if (!$user->hasPermissionTo($this->pathsToPermissionName($paths))) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * 前端验证权限
     * POST接受一个数组，以key为权限名，value为空；
     * 将对应权限填入value后，返回数组
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    private function authFrontend(User $user)
    {
        $params = request()->all();

        foreach ($params as $key => $item) {
            try {
                $params[$key] = $user->hasPermissionTo($key);
            } catch (\Exception $e) {
                $params[$key] = false;
            }
        }

        return response()->json(['data' => $params]);
    }


    /**
     * 将url转换为permission所需格式 例如:user/all --> user-all
     * @param $paths
     * @return string
     */
    private function pathsToPermissionName($paths)
    {
        $name = ''; $delimiter = '-';

        foreach ($paths as $key => $path) {
            if (in_array($path, $this->path_except)) {
                continue;
            }

            if ($key == (count($paths) - 1)) {
                $name .= $path;
            } else {
                $name .= ($path . $delimiter);
            }

        }
        return $name;
    }


    /**
     * 判断是否是超级管理员
     * @param $user
     * @return bool
     */
    private function isSuperAdmin($user)
    {
        return in_array($user->username, $this->super_admin_name);
    }


    /**
     * 解析url
     */
    private function resolveUrl()
    {
        $path = request()->path();

        return explode('/', $path);
    }


    /**
     * 判断是否是前端权限验证请求
     * @param array $paths
     * @return bool
     */
    private function isFrontendAuth(array $paths)
    {
        return $paths[1] === $this->frontend_sign ? true : false;
    }
}
