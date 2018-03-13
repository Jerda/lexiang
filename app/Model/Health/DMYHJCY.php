<?php

namespace App\Model\Health;

use Illuminate\Database\Eloquent\Model;

class DMYHJCY extends Model
{
    protected $table = 'DMYHJCY';

    protected $fillable = ['health_id', 'PWV', 'ABI', 'ASI', 'C1', 'C2', 'SV', 'CO', 'SVR', 'ET',
        'MAP', 'PP', 'HR', 'BMI'];
}
