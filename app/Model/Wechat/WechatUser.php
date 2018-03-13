<?php

namespace App\Model\Wechat;

use Illuminate\Database\Eloquent\Model;

class WechatUser extends Model
{
    protected $table = 'wechat_user';

    public $timestamps = false;

    protected $fillable = ['user_id', 'openid',
        'nickname', 'wechat_info', 'avatar', 'qrcode', 'sex', 'country', 'province', 'city', 'subscribe_time',
        'wechat_remark', 'groupid', 'tagid_list'];


    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
}
