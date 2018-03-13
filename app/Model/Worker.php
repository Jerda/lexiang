<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Worker extends Pivot
{
    protected $table = 'worker';

    protected $fillable = ['user_id', 'enterprise_id'];

}
