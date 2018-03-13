<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PermissionName extends Model
{
    protected $table = 'permission_name';

    protected $fillable = ['name', 'permission_id', 'level'];
}
