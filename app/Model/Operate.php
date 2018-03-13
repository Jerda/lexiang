<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Operate extends Model
{
    protected $table = 'operate';

    protected $fillable = ['type', 'content', 'operator', 'model', 'model_id'];
}
