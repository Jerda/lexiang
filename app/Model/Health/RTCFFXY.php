<?php

namespace App\Model\Health;

use Illuminate\Database\Eloquent\Model;

class RTCFFXY extends Model
{
    protected $table = 'RTCFFXY';

    protected $fillable = ['health_id', 'wu_ji_yan', 'zhi_fang_zhong', 'dan_bai_zhi', 'ji_rou_zhong',
        'qu_zhi_ti_zhong', 'xi_bao_wai_ye', 'xi_bao_nei_ye', 'shen_ti_shui_fen', 'shi_ji_ti_zhong',
        'fu_zhong_zhi_shu', 'ti_zhong_zhi_shu', 'ti_zhi_fang_lv'];
}
