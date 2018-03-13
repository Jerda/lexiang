<?php
namespace App\Libraries\Health\Traits;

use App\Model\User;
use Facades\App\Libraries\File;
use Illuminate\Support\Facades\DB;
use App\Model\Health as HealthModel;

trait Health
{
    /**
     * @param $user_id
     * @param $data
     */
    private function create($user_id, $data)
    {
        $this->handelPicture($data);

        DB::transaction(function() use ($user_id, $data) {
            $health = HealthModel::create([
                'user_id' => $user_id,
                'NO' => $this->NO()
            ]);

            $data['health_id'] = $health->id;

            foreach ($this->health_models as $model) {
                DB::table($model)->create($data);
            }
        });
    }


    /**
     * 获取详情健康详情并合并数据
     * @param $health_id
     * @return array
     */
    private function detail($health_id)
    {
        $data = [];

        foreach ($this->health_models as $model) {
            $res = DB::table($model)->where('health_id', $health_id)->first();

            $data[$model] = json_decode( json_encode($res),true);
        }

        return $data;
    }


    /**
     * 健康表号
     * @return string
     */
    private function NO()
    {
        return date(YmdHis, time()).rand(1000, 9999);
    }


    private function fields()
    {
        return [
            'chao_shen_gu_mi_du_fen_xi_yi' => [
                'name' => '超声骨密度分析仪',
                'db_name' => 'CSGMDFXY',
                'T' => 'T值',
                'Z' => 'Z值',
                'BQI' => 'BQI',
                'adult_percent' => 'adult percent',
                'age_percent' => 'age percent',
                'PAB' => 'PAB',
                'RRF' => 'RRF',
                'EOA' => 'EOA',
            ],
            'dong_mai_ying_hua_jian_ce_yi' => [
                'name' => '动脉硬化检测仪',
                'db_name' => 'DMYHJCY',
                'PWV' => '脉搏波传导速度',
                'ABI' => '踝臂指数',
                'ASI' => '动脉硬化指数',
                'C1' => '大动脉顺应性指数',
                'C2' => '小动脉顺应性指数',
                'SV' => '每搏心输出量',
                'CO' => '每分心输出量',
                'SVR' => '外周阻力',
                'ET' => '左心室收缩时间',
                'zuo_shang_zhi_shou_suo_ya' => '左上肢收缩压',
                'zuo_shang_zhi_shu_zhang_ya' => '左上肢舒张压',
                //未完
                'MAP' => '四肢平均压',
                'PP' => '四肢脉压',
                'HR' => '心率',
                'BMI' => '体重指数',
            ],
            'ren_ti_cheng_fen_fen_xi_yi' => [
                'name' => '人体成分分析仪',
                'db_name' => 'RTCFFXY',
                'wu_ji_yan' => '无机盐',
                'zhi_fang_zhong' => '脂肪重',
                'dan_bai_zhi' => '蛋白质',
                'ji_rou_zhong' => '肌肉重',
                'qu_zhi_ti_zhong' => '去肢体重',
                'xi_bao_wai_ye' => '细胞外液',
                'xi_bao_nei_ye' => '细胞内液',
                'shen_ti_shui_fen' => '身体水分',
                'shi_ji_ti_zhong' => '实际体重',
                'fu_zhong_zhi_shu' => '浮肿指数',
                'ti_zhong_zhi_shu' => '体重指数',
                'ti_zhi_fang_lv' => '体脂肪率',
            ],
            'shi_er_dao_xin_dian_tu_ji' => [
                'name' => '十二导心电图机',
                'db_name' => 'SEDXDTJ',
                'xin_fang_fei_da' => '心房肥大',
                'xin_shi_fei_da' => '心室肥大',
                'xin_ji_que_xue' => '心肌缺血',
                'xin_ji_geng_si' => '心肌梗死',
                'xin_lv_shi_chang' => '心率失常',
                'dou_xing_xin_lv_shi_chang' => '窦性心律失常',
                'qi_qian_shou_suo' => '期前收缩',
                'yi_wei_xin_dong_guo_su' => '异位心动过速',
                'pu_dong_yu_chan_dong' => '扑动与颤动',
                'ji_xing_xin_ji_geng_se' => '急性心肌梗塞',
                'chen_jiu_xin_ji_geng_se' => '陈旧心肌梗塞',
                'xin_ji_que_yang' => '心肌缺氧',
                'fei_te_yi_xing_ST_T_bian_hua' => '非特异性ST-T变化',
                'xin_lv_bu_zheng' => '心率不整',
                'dou_xing_xin_bo_guo_huan' => '窦性心博过缓',
                'dou_xing_xin_bo_guo_su' => '窦性心博过速',
            ],
            'shen_gao_ti_zhong_yi' => [
                'name' => '身高体重仪',
                'db_name' => 'SGTZY',
                'shen_gao' => '身高',
                'ti_zhong' => '体重',
            ],
            'xue_zhi_fen_xi_yi' => [
                'name' => '血脂分析仪',
                'db_name' => 'XZFXY',
                'zong_dan_gu_chun' => '总胆固醇',
                'gao_mi_du_zhi_dan_bai_dan_gu_chun' => '高密度脂蛋白胆固醇',
                'gan_you_san_zhi' => '甘油三酯',
                'di_mi_du_dan_gu_chun' => '低密度胆固醇',
            ],
            'hong_wai_ti_wen_yi' => [
                'name' => '红外体温仪',
                'db_name' => 'HWTWY',
                'ti_wen' => '体温',
                'biao_mian_wen_du' => '表面温度',
            ],
            'xue_yang_yi' => [
                'name' => '血氧仪',
                'db_name' => 'XYY',
                'spo2' => '血氧饱和度',
            ],
            'xue_tang_yi' => [
                'name' => '血糖仪',
                'db_name' => 'XTY',
                'xue_tang_zhi' => '血糖值',
            ],
            'niao_ye_fen_xi_yi' => [
                'name' => '尿液分析仪',
                'db_name' => 'NYFXY',
                'bai_xi_bao' => '白细胞',
                'ya_xiao_suan_yan' => '亚硝酸盐',
                'niao_dan_yuan' => '尿胆原',
//        'dan_bao_zhi' => '蛋白质', //重复
                'PH' => 'PH值',
                'qian_xue' => '潜血',
                'bi_zhong' => '比重',
                'tong_ti' => '酮体',
                'dan_hong_su' => '胆红素',
                'pu_tao_tang' => '葡萄糖',
                'wei_sheng_su_C' => '维生素C',
            ],
        ];
    }


    /**
     * 验证录入字段
     * @param $data
     * @throws \Exception
     */
    private function validatorInput($data)
    {
        $validator = Validator::make($data, [
            'username' => 'required',
            'sex' => 'required',
            'age' => 'required',
            'date' => 'required',
            'idcard' => 'required_without:mobile',
            'mobile' => 'required_without:idcard',
            'validate' => 'required'
        ]);

        if (!$validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }


    /**
     * 验证发送录入客户端有效性
     * @param $code
     * @throws \Exception
     */
    private function validateClient($code)
    {
        if (!md5($this->validate_code, date('Ymd')) === $code)
            throw new \Exception(trans('system.health_client_validate_error'));
    }


    /**
     * 验证用户
     * @param $idcard
     * @param $mobile
     * @return string
     * @throws \Exception
     */
    private function validateUser($idcard, $mobile)
    {
        $user = '';

        if (!empty($idcard)) {
            $user = User::where('idcard', $idcard)->first();
        }

        if (!empty($mobile)) {
            $user = User::where('mobile', $mobile)->first();
        }

        if (empty($user)) {
            throw new \Exception(trans('system.user_not_exists'));
        }

        return $user;
    }


    /**
     * 图片处理
     * @param $data
     */
    private function handelPicture(&$data)
    {
        foreach ($this->pictures as $picture) {
            $data[$picture] = File::store($data[$picture]);
        }
    }
}
