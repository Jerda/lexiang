<?php

namespace App\Model\Health;

use Illuminate\Database\Eloquent\Model;

class XTY extends Model
{
    protected $table = 'XTY';

    protected $fillable = ['health_id', 'xue_tang_zhi'];
}
