<?php

namespace App\Model\Health;

use Illuminate\Database\Eloquent\Model;

class SGTZY extends Model
{
    protected $table = 'SGTZY';

    protected $fillable = ['health_id', 'shen_gao', 'ti_zhong'];
}
