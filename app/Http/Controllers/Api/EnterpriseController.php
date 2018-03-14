<?php
namespace App\Http\Controllers\Api;

use App\Libraries\Operate;
use App\Model\User;
use App\Model\Enterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EnterpriseController extends BaseController
{
    /*
     * ------------------------------------------------------------------------
     * 企业控制器
     * ------------------------------------------------------------------------
     */

    /**
     * 获取行业类型
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEnterpriseType()
    {
        return response()->json(['data' => config('code.enterprise.type')]);
    }


    /**
     * 注册企业
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        $data = request()->all();

        try {
            $this->validateRegister($data);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], config('response_error.field_validate'));
        }

        Enterprise::create(array_merge($data, ['status' => config('code.enterprise.status.wait_examine')]));

        return response()->json(['message' => trans('system.add_success')]);
    }


    /**
     * 根据ID获取企业
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        $enterprise = Enterprise::find(request()->input('enterprise_id'));

        return response()->json(['data' => $enterprise]);
    }


    /**
     * 获取状态正常的企业
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function normal(Request $request)
    {
        $enterprises = Enterprise::normal()->where(formatWhere(['name', 'legal_person', 'enterprise_type_id']))
            ->paginate($request->input('limit'));

        return response()->json(['data' => $enterprises]);
    }


    /**
     * 获取待审核的企业
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function waitExamine(Request $request)
    {
        $enterprises = Enterprise::waitExamine()->where(formatWhere(['name', 'legal_person', 'enterprise_type_id']))
            ->paginate($request->input('limit'));

        return response()->json(['data' => $enterprises]);
    }


    /**
     * 驳回企业申请
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(Request $request)
    {
        $this->modifyStatus($request->input('enterprise_id'), config('code.enterprise.status.reject'));

        Operate::record([
            'type' => config('code.operate_type.reject.enterprise'),
            'content' => $request->input('content'),
            'model' => 'Enterprise',
            'model_id' => $request->input('enterprise_id')
        ]);

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 获取驳回记录
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
            'model' => 'Enterprise',
            'model_id' => $request->input('model_id'),
        ]);

        return response()->json(['data' => $data]);
    }


    /**
     * 修改企业状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Request $request)
    {
        $this->modifyStatus($request->input('enterprise_id'), $request->input('status'));

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 添加员工
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function agreeWorkerJoin(Request $request)
    {
        User::where('id', $request->input('user_id'))->update(['status' => config('code.user.status.worker')]);

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 移除员工
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeWorker(Request $request)
    {
        $user = User::where('id', $request->input('user_id'))->first();

        DB::transaction(function() use ($user) {
            $user->enterprises()->detach();

            $user->status = config('code.user.status.normal');

            $user->save();
        });


        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 同意企业申请
     * @return \Illuminate\Http\JsonResponse
     */
    public function agree()
    {
        $this->modifyStatus(request()->input('enterprise_id'), config('code.enterprise.status.normal'));

        return response()->json(['message' => trans('system.operate_success')]);
    }


    /**
     * 根据名称获取企业
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByName(Request $request)
    {
        $enterprises = Enterprise::where(formatWhere(['name']))->where('status', 1)->get();

        return response()->json(['data' => $enterprises]);
    }


    public function checkIsRegister()
    {

    }


    public function checkUserIsAdmin()
    {
        $this->user();
    }


    /**
     * 验证企业注册信息
     * @param $data
     * @throws \Exception
     */
    private function validateRegister($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
            'legal_person' => 'required',
            'linker' => 'required',
            'linker_mobile' => 'required',
            'linker_email' => 'required|email',
            'enterprise_type_id' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'address' => 'required',
        ], [
            'required' => trans('system.must_complete_information'),
            'linker_email.email' => trans('system.email_wrong'),

        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }


    /**
     * 修改企业状态
     * @param $enterprise_id
     * @param $status
     */
    private function modifyStatus($enterprise_id, $status)
    {
        $enterprise = Enterprise::find($enterprise_id);

        $enterprise->status = $status;

        $enterprise->save();
    }
}
