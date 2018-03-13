<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Health extends Model
{
    protected $table = 'health';

    protected $fillable = ['user_id', 'NO', 'health_detail_id'];

    /**
     * 表名
     * CSGMDFXY:超声骨密度分析仪 DMYHJCY:动脉硬化检测仪 RTCFFXY:人体成分分析仪 SEDXDTJ:十二导心电图机 RTCFFXY:人体成分分析仪
     * SGTZY:身高体重仪 XZFXY:血脂分析仪 HWTWY:红外体温仪 XYY:血氧仪 XTY:血糖仪 NYFXY:尿液分析仪
     * @var array
     */
    protected $health_models = [
        'CSGMDFXY', 'DMYHJCY', 'RTCFFXY', 'SEDXDTJ', 'SGTZY',
        'XZFXY', 'HWTWY', 'XYY', 'XTY', 'NYFXY',
    ];


    public function detail($health_id)
    {
        return $this->where('id', $health_id)->with('CSGMDFXY')->with('DMYHJCY')->with('RTCFFXY')
            ->with('SEDXDTJ')->with('SGTZY')->with('XZFXY')->with('HWTWY')->with('XYY')
            ->with('XTY')->with('NYFXY')->first();
    }


    protected function CSGMDFXY()
    {
        return $this->hasMany('App\Model\CSGMDFXY');
    }

    protected function DMYHJCY()
    {
        return $this->hasMany('App\Model\DMYHJCY');
    }

    protected function RTCFFXY()
    {
        return $this->hasMany('App\Model\RTCFFXY');
    }

    protected function SEDXDTJ()
    {
        return $this->hasMany('App\Model\SEDXDTJ');
    }

    protected function SGTZY()
    {
        return $this->hasMany('App\Model\SGTZY');
    }

    protected function XZFXY()
    {
        return $this->hasMany('App\Model\XZFXY');
    }

    protected function HWTWY()
    {
        return $this->hasMany('App\Model\HWTWY');
    }

    protected function XYY()
    {
        return $this->hasMany('App\Model\XYY');
    }

    protected function XTY()
    {
        return $this->hasMany('App\Model\XTY');
    }

    protected function NYFXY()
    {
        return $this->hasMany('App\Model\NYFXY');
    }
}
