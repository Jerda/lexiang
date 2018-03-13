<?php
namespace App\Http\Controllers\Api;

use App\Libraries\Operate;
use App\Model\User;
use App\Model\Enterprise;
use Illuminate\Http\Request;
use App\Model\EnterpriseAdmin;
use Illuminate\Support\Facades\Validator;

class WorkerController extends BaseController
{
    /**
     * --------------------------------------
     * 员工 控制器
     * --------------------------------------
     */


    /**
     * 获取所有员工
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        $workers = User::normalWorker()->where(formatWhere(['name', 'mobile']))
            ->whereHas('enterprises', function($query) use ($request) {
                $query->where('enterprise_id', $request->input('enterprise_id'));
            })->with('wechat')->with('concern')->paginate($request->input('limit'));

        return response()->json(['data' => $workers]);
    }


    /**
     * 获取待审批员工
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWaitExamineWorker(Request $request)
    {
        $workers = User::abnormalWorker()->where(formatWhere(['name', 'mobile']))
            ->whereHas('enterprises', function($query) use ($request) {
                $query->where('enterprise_id', $request->input('enterprise_id'));
        })->with('wechat')->with('concern')->paginate($request->input('limit'));

        return response()->json(['data' => $workers]);
    }


    /**
     * 根据企业获取员工
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByEnterprise(Request $request)
    {
        $workers = Enterprise::where('id', $request->input('enterprise_id'))
            ->with(['workers' => function($query) {
                $query->with('wechat')->with('concern');
            }])->first();

        return response()->json(['data' => $workers]);
    }


    /**
     * 获取员工列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListByEnterprise()
    {
        $enterprise = Enterprise::find(request()->input('enterprise_id'));

        return response()->json(['data' => $enterprise->workers]);
    }


    /**
     * 根据当前验证用户(登录用户)对应企业获取对应姓名用户
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByNameForAuthUser()
    {
        //查询当前登录管理员对应的企业
        $admin = EnterpriseAdmin::where('user_id', $this->user()->id)->first();

        $users = $this->getByNameInEnterprise($admin->enterprise_id, request()->input('name'));

        return response()->json(['data' => $users]);
    }


    /**
     * 根据姓名和企业ID获取用户
     * 注意:该方法企业ID为post提交数据，可查询所有企业员工;注意权限
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByName()
    {
        $users = $this->getByNameInEnterprise(request()->input('enterprise_id'), request()->input('name'));

        return response()->json(['data' => $users]);
    }


    /**
     * 通过手机号获取用户，如果该手机号没有被注册，返回403
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByMobile(Request $request)
    {
        $user = $this->getByMobileInEnterprise($request->input('enterprise_id'), $request->input('mobile'));

        return response()->json(['data' => $user]);
    }


    /**
     * 驳回员工申请
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(Request $request)
    {
        User::where('id', $request->input('user_id'))->update(['status' => config('code.user.status.worker_reject')]);

        Operate::record([
            'type' => config('code.operate_type.reject.worker'),
            'content' => $request->input('content'),
            'model' => 'User',
            'model_id' => $request->input('user_id')
        ]);

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 获取驳回记录列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejectList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'model_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], config('response_error.validate'));
        }

        $data = Operate::listByOne([
            'model' => 'User',
            'model_id' => $request->input('model_id'),
        ]);

        return response()->json(['data' => $data]);
    }


    /**
     * 根据姓名和企业ID获取员工
     * @param $enterprise_id
     * @param $name
     * @return mixed
     */
    private function getByNameInEnterprise($enterprise_id, $name)
    {
        return User::where('name', 'like', '%'.$name.'%')
            ->whereHas('enterprises', function($query) use ($enterprise_id) {
                $query->where('enterprise_id', $enterprise_id);
            })->get();
    }


    /**
     * 根据手机号和企业ID获取员工
     * @param $enterprise_id
     * @param $mobile
     * @return mixed
     */
    private function getByMobileInEnterprise($enterprise_id, $mobile)
    {
        return User::where('mobile', 'like', '%'.$mobile.'%')
            ->whereHas('enterprises', function($query) use ($enterprise_id) {
                $query->where('enterprise_id', $enterprise_id);
            })->get();
    }
}
