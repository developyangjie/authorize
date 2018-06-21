<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //公众号设置
    public function appInfo(){
        $wx_id = session('admin_user_info.wx_id');
        $user_info = app('User')->getAppInfo($wx_id);
        switch ($user_info['service_type'])
        {
            case 0:case 1:
                $user_info['service_name'] = '订阅号';
            break;
            case 2:
                $user_info['service_name'] = '服务号';
            default:
                $user_info['service_name'] = '未知';
        }
        switch ($user_info['verify_type'])
        {
            case 0:
                $user_info['verify_name'] = '微信认证';
                break;
            case 1:
                $user_info['verify_name'] = '新浪微博认证';
                break;
            case 2:
                $user_info['verify_name'] = '腾讯微博认证';
                break;
            case 3:
                $user_info['verify_name'] = '代表已资质认证通过但还未通过名称认证';
                break;
            case 4:
                $user_info['verify_name'] = '代表已资质认证通过、还未通过名称认证，但通过了新浪微博认证';
                break;
            case 5:
                $user_info['verify_name'] = '代表已资质认证通过、还未通过名称认证，但通过了腾讯微博认证';
                break;
            default:
                $user_info['verify_name'] = '未认证';
                break;
        }
        return view('setting.appSetting',[
            'user_info' => $user_info
        ]);
    }
    //刷新用户信息
    public function flashUserInfo(){
        $wx_id = session('admin_user_info.wx_id');
        $app_id = session('admin_user_info.app_id');
        $app_secret = session('admin_user_info.app_secret');
        //更新用户信息
        $res = app('User')->flashAppInfo($wx_id,$app_id,$app_secret);
        $user_info = app('User')->getAppInfo($wx_id);
        unset($user_info['password']);
        session(['admin_user_info'=>$user_info]);
        return back();
    }
    //认证过期时间
    public function intoExpireIn(Request $request)
    {
        $this->validate($request, [
            'date_str' => 'required|date'
        ]);
        $date_str = $request->input('date_str');
        $expire_time = strtotime($date_str);
        $wx_id = session('admin_user_info.wx_id');
        $res = app('User')->updateExpireTime($expire_time,$wx_id);
        return $res;
    }
}
