<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ConcernInvite extends Model
{
    protected $table = 'concern_invite';

    protected $fillable = ['user_id', 'invitees_user_id', 'status'];

    public function user()
    {
        return $this->hasOne('App\Model\User', 'id', 'user_id');
    }

    public function inviteUser()
    {
        return $this->hasOne('App\Model\User', 'id', 'invitees_user_id');
    }


}
