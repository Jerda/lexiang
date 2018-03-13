<?php

namespace App\Model\Health;

use Illuminate\Database\Eloquent\Model;

class NYFXY extends Model
{
    protected $table = 'NYFXY';

    protected $fillable = ['health_id', 'bai_xi_bao', 'ya_xiao_suan_yan', 'niao_dan_yuan', 'dan_bao_zhi',
        'PH', 'qian_xue', 'bi_zhong', 'tong_ti', 'dan_hong_su', 'pu_tao_tang', 'wei_sheng_su_C'];
}
