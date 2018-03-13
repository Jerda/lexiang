<?php

namespace App\Model\Health;

use Illuminate\Database\Eloquent\Model;

class HWTWY extends Model
{
    protected $table = 'HWTWY';

    protected $fillable = ['health_id', 'ti_wen', 'biao_mian_wen_du'];
}
