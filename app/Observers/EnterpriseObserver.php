<?php
namespace App\Observers;

use App\Model\Enterprise;

class EnterpriseObserver
{
    /*
     * ---------------------------------------------------
     * 企业观察器
     * ---------------------------------------------------
     */

    public function retrieved(Enterprise $enterprise)
    {
        $enterprise->typeCodeToText();
    }


    public function saving(Enterprise $enterprise)
    {
        unset($enterprise->enterprise_type);
    }
}
