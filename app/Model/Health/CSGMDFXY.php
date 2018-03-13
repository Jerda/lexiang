<?php

namespace App\Model\Health;

use Illuminate\Database\Eloquent\Model;

class CSGMDFXY extends Model
{
    protected $table = 'CSGMDFXY';

    protected $fillable = ['health_id', 'T', 'Z', 'BQI', 'adult_percent', 'age_percent', 'PAB',
        'RRF', 'EOA'];
}
