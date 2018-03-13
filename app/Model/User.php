<?php

namespace App\Model;

use Laravel\Passport\HasApiTokens;
use App\Libraries\Wechat\Traits\RegisterWechatUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    use RegisterWechatUser;
    use HasRoles;

    public static $wechat_data = [];    //配合RegisterWechatUser使用

    protected $guard_name = 'api';  //permission使用

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'mobile', 'name', 'user_num', 'type', 'status', 'sex',
        'type', 'idcard', 'idcard_img', 'qq', 'remark', 'is_admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function wechat()
    {
        return $this->hasOne('App\Model\Wechat\WechatUser');
    }


    public function concern()
    {
        return $this->hasMany('App\Model\Concern');
    }


    public static function setWechatData($data)
    {
        static::$wechat_data = $data;
    }


    public static function getWechatData()
    {
        return static::$wechat_data;
    }


    public function enterprises()
    {
        return $this->belongsToMany('App\Model\Enterprise');
    }


    public function scopeNormal($query)
    {
        $query->where('status', '<>', 0);
    }


    public function scopeAdmins($query)
    {
        $query->where('is_admin', 1);
    }


    public function scopeNormalWorker($query)
    {
        $query->where('status', config('code.user.status.worker'));
    }


    public function scopeAbnormalWorker($query)
    {
        $query->where('status', '<>', config('code.user.status.worker'));
    }


    /**
     * 因为使用的是username而不是email,故passport用此方法进行判断
     * @param $login
     * @return mixed
     */
    public function findForPassport($login)
    {
        return $this->where('username', $login)->first();
    }


    /**
     * 添加用户年龄
     */
    public function addAge()
    {
        if (!empty($this->birthday)) {
            $birthday = strtotime($this->birthday);
            $this->age = intval((time() - $birthday) / (3600 * 24 *365));
        }
    }
}
