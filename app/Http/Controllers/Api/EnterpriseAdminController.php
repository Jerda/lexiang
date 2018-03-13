<?php
namespace App\Http\Controllers\Api;

use App\Model\Enterprise;
use Illuminate\Http\Request;
use App\Model\EnterpriseAdmin;

class EnterpriseAdminController extends BaseController
{
    /**
     * --------------------------------------
     * 企业管理员 控制器
     * --------------------------------------
     */


    public function getEnterpriseByAdmin()
    {
        $res = EnterpriseAdmin::where('user_id', $this->userId())->first();

        $enterprise = Enterprise::where('id', $res->enterprise_id)->first();

        return response()->json(['data' => $enterprise]);
    }


    /**
     * 根据企业ID获取管理员
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $admins = EnterpriseAdmin::where('enterprise_id', request()->input('enterprise_id'))
            ->with(['user' => function($query) {
                $query->with('wechat');
            }])->get();

        return response()->json(['data' => $admins]);
    }


    /**
     * 添加企业管理员
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        try {
            EnterpriseAdmin::create([
                'enterprise_id' => $request->input('enterprise_id'),
                'user_id' => $request->input('user_id'),
                'main' => $request->input('main')]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], config('response_error.validate'));
        }

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 删除企业管理员
     * @return \Illuminate\Http\JsonResponse
     */
    public function del()
    {
        EnterpriseAdmin::where([
            ['user_id', request()->input('user_id')],
            ['enterprise_id', request()->input('enterprise_id')]
        ])->delete();

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 将管理员设置为主管理员
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAdminIsMain()
    {
        return $this->setAdminType(config('code.enterprise_admin.main'));
    }

    /**
     * 设置为非主管理员
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAdminIsNotMain()
    {
        return $this->setAdminType(config('code.enterprise_admin.not_main'));
    }


    /**
     * 设置管理员类型(主管理员/管理员)
     * @param $main
     * @return \Illuminate\Http\JsonResponse
     */
    private function setAdminType($main)
    {
        $admin = EnterpriseAdmin::where([
            ['user_id', request()->input('user_id')],
            ['enterprise_id', request()->input('enterprise_id')],
        ])->first();

        if (empty($admin)) {
            return response()->json(['message' => trans('system.user_is_not_admin')], config('response_error.validate'));
        }

        $admin->main = $main;
        $admin->save();

        return response()->json(['message' => trans('system.operate_success')]);
    }
}
