<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EnterpriseUser extends Model
{
    protected $table = 'enterprise_user';

    protected $fillable = ['enterprise_id', 'user_id'];
}
