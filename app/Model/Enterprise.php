<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    protected $table = 'enterprise';

    protected $fillable = ['name', 'legal_person', 'linker', 'linker_mobile', 'linker_email',
        'enterprise_type_id', 'province', 'city', 'district', 'address', 'status'];

    public function workers()
    {
        return $this->belongsToMany('App\Model\User');
    }

    
    public function scopeNormal()
    {
        return $this->where('status', 1);
    }


    public function scopeWaitExamine()
    {
        return $this->where('status', '<>', 1);
    }


    /**
     * 将企业类型编号转为字符
     * @return mixed
     */
    public function typeCodeToText()
    {
        $this->enterprise_type = config('code.enterprise.type.'.$this->enterprise_type_id);
    }
}
