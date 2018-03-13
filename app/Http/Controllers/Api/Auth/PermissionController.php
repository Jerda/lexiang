<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Libraries\AdminAuth;

class PermissionController extends Controller
{
    public function auth()
    {
        return AdminAuth::auth();
    }
}
