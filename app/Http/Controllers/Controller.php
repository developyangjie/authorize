<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getAuthToken()
    {
        $wx_id = session('admin_user_info.wx_id');
        $authorization_code = session('admin_user_info.auth_code');
        $component_appid = env('APP_XUET_ID');
        $component_secret = env('APP_XUET_SECRET');
        $res = app('Wx')->getAuthToken($wx_id,$component_appid,$component_secret,$authorization_code);
        return $res;
    }
}
