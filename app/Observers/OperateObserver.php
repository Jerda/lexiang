<?php
namespace App\Observers;

use App\Model\User;
use App\Model\Operate;

class OperateObserver
{
    /*
     * ---------------------------------------------------
     * 操作记录观察器
     * ---------------------------------------------------
     */


    public function creating(Operate $operate)
    {
//        $operate->operator = auth()->guard('api')->user()->id;
        $operate->operator = 11;
    }


    public function retrieved(Operate $operate)
    {
        $user = User::find($operate->operator);

        $operate->operator = $user->username;
    }
}
