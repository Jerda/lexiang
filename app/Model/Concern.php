<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Concern extends Model
{
    protected $table = 'concern';

    protected $fillable = ['user_id', 'concern_user_id', 'relation'];


    public function concernUser()
    {
        return $this->belongsTo('App\Model\User', 'concern_user_id', 'id');
    }
}
