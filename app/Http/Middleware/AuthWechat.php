<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Facades\App\Libraries\Wechat\WechatTool;

class AuthWechat
{
    /*
    |--------------------------------------------------------------------------
    | 微信网页授权
    |--------------------------------------------------------------------------
    */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty(Auth::check())) {
            session(['target_url' => '/wechat/index']);

            return WechatTool::oauth();
        }

        return $next($request);
    }
}
