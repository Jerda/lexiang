<?php

namespace App\Model\Health;

use Illuminate\Database\Eloquent\Model;

class XZFXY extends Model
{
    protected $table = 'XZFXY';

    protected $fillable = ['health_id', 'zong_dan_gu_chun', 'gao_mi_du_zhi_dan_bai_dan_gu_chun',
        'gan_you_san_zhi', 'di_mi_du_dan_gu_chun'];
}
