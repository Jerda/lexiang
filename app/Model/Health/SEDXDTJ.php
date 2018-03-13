<?php

namespace App\Model\Health;

use Illuminate\Database\Eloquent\Model;

class SEDXDTJ extends Model
{
    protected $table = 'SEDXDTJ';

    protected $fillable = ['health_id', 'xin_fang_fei_da', 'xin_shi_fei_da', 'xin_ji_que_xue', 'xin_ji_geng_si',
        'xin_lv_shi_chang', 'dou_xing_xin_lv_shi_chang', 'qi_qian_shou_suo', 'yi_wei_xin_dong_guo_su',
        'pu_dong_yu_chan_dong', 'ji_xing_xin_ji_geng_se', 'chen_jiu_xin_ji_geng_se', 'xin_ji_que_yang',
        'fei_te_yi_xing_ST_T_bian_hua', 'xin_lv_bu_zheng', 'dou_xing_xin_bo_guo_huan', 'dou_xing_xin_bo_guo_su'];
}
