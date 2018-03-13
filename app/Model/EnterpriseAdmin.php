<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EnterpriseAdmin extends Model
{
    protected $table = 'admin_enterprise';

    protected $fillable = ['user_id', 'enterprise_id', 'main'];


    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }


    /**
     * 判断企业是否拥有管理员，如果没有，添加第一个管理员为主管理员
     * @param $enterprise_id
     */
    public function hasAdmin($enterprise_id)
    {
        $this->main = empty($this->where('enterprise_id', $enterprise_id)->first())
            ? config('code.enterprise_admin.main') : $this->main;
    }


    /**
     * 重复添加
     * @param $enterprise_id
     * @param $user_id
     * @throws \Exception
     */
    public function repeat($enterprise_id, $user_id)
    {
        if ($this->where([
            ['enterprise_id', $enterprise_id],
            ['user_id', $user_id],
        ])->first()) {
            throw new \Exception(trans('system.can_not_add_repeat'));
        }
    }
}
