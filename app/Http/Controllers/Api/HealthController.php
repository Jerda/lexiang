<?php
namespace App\Http\Controllers\Api;

use App\Model\Health;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HealthController extends BaseController
{
    use \App\Libraries\Health\Traits\Health;

    /**
     * --------------------------------------
     * 健康数据 控制器
     * --------------------------------------
     */

    protected $pictures = [];

    /**
     * 表名
     * CSGMDFXY:超声骨密度分析仪 DMYHJCY:动脉硬化检测仪 RTCFFXY:人体成分分析仪 SEDXDTJ:十二导心电图机
     * SGTZY:身高体重仪 XZFXY:血脂分析仪 HWTWY:红外体温仪 XYY:血氧仪 XTY:血糖仪 NYFXY:尿液分析仪
     * @var array
     */
    public $health_models = [
        'CSGMDFXY', 'DMYHJCY', 'RTCFFXY', 'SEDXDTJ', 'SGTZY',
        'XZFXY', 'HWTWY', 'XYY', 'XTY', 'NYFXY',
    ];


    /**
     * 根据用户ID获取用户健康检测列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListByUserID()
    {
        $user_id = $this->userId();
        //根据用户获取健康数据列表
        $list = Health::where('user_id', $user_id)
            ->orderBy('created_at', 'asc')->get();

        return response()->json(['data' => $list]);
    }


    /**
     * 获取当前验证用户健康检查列表
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function getListByUser()
    {
        $user_id = $this->userId();

        $list = Health::where('user_id', $user_id)
            ->orderBy('created_at', 'asc')->get();

        return response()->json(['data' => $list]);
    }*/


    /**
     * 获取健康详情数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetail(Request $request)
    {
        $data = $this->detail($request->input('health_id'));

        return response()->json(['data' => $data]);
    }


    /**
     * 获取健康表字段
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFields()
    {
        return response()->json(['data' => $this->fields()]);
    }


    /**
     * 录入数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function input(Request $request)
    {
        try {
            $this->validatorInput($request->all());

            $this->validateClient($request->input('validate'));

            $user = $this->validateUser($request->input('idcard'), $request->input('mobile'));

            $this->create($user->id, $request->all());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], config('response_error.validate'));
        }

        return response()->json(['message' => trans('system.health_input_success')]);
    }
}
