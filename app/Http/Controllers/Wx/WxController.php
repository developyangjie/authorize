<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Request;


class WxController extends Controller
{

    public function setWx()
    {
        $org_name = request()->input('org_name');
        $app_id = request()->input('app_id');
        $app_secret = request()->input('app_secret');
        $auth_file_name = request()->input('auth_file_name');
        $auth_file_data = request()->input('auth_file_data');
        $ret = app('Wx')->setWx($org_name,$app_id,$app_secret,$auth_file_name,$auth_file_data);
        if($ret['state']){
            return ['code'=>"200"];
        }else{
            return ['code'=>"400",'msg'=>$ret['msg']];
        }
    }

    public function getWx()
    {
        $app_id = request()->input('app_id');
        $ret = app('Wx')->getWx($app_id);
        if($ret['state']){
            return ['code'=>"200",'data'=>$ret['data']];
        }else{
            return ['code'=>"400",'msg'=>$ret['msg']];
        }
    }


    public function msgWxSend()
    {
        $msgStr = Request::input('msg');
        if(mb_strlen($msgStr) > 0 ){
            $msgList = json_decode(base64_decode($msgStr),true);
            if(is_array($msgList) && count($msgList) > 0){
                if(count($msgList) <= 100 ){
                    $ret = app('Wx')->wxSend($msgList);
                    $data = ['code'=>'200',"data"=>$ret];
                }else{
                    $data = ['code'=>"200",'msg'=>'单次发送消息数量不能超过100条'];
                }
            }else{
                $data = ['code'=>"400",'msg'=>'消息内容为空!'];
            }
        }else{
            return response('File not found', 404)->header('Content-Type', 'text/plain');
        }
        return $data;
    }


    public function getPreAuthCode(){
        $app_id = env('APP_XUET_ID');
        $app_secret = env('APP_XUET_SECRET');
        $res = app('Wx')->getPreAuthCode($app_id,$app_secret);
        return $res;
    }
}
