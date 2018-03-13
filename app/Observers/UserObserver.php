<?php
namespace App\Observers;

use App\Model\User;

class UserObserver
{
    /*
     * ---------------------------------------------------
     * 用户观察器
     * ---------------------------------------------------
     */

    public function retrieved(User $user)
    {
        $user->addAge();
    }


    public function saving(User $user)
    {
        unset($user->age);
    }
}
