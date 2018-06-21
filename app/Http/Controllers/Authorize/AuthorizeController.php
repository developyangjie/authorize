<?php

namespace App\Http\Controllers\Authorize;

use App\Http\Controllers\Controller;

use Request;



class AuthorizeController extends Controller
{
    public function getAccessToken()
    {
        $app_id = request()->input('app_id');
        $ret = app('Authorize')->getAccessToken($app_id);
        if($ret['state']){
            return ['code'=>"200",'data'=>[
                'access_token'=>$ret['data']['access_token'],
                'access_token_expire_time'=>$ret['data']['access_token_expire_time']
            ]];
        }else{
            return ['code'=>"400",'msg'=>$ret['msg']];
        }
    }


    public function getJsApiTicket()
    {
        $app_id = request()->input('app_id');
        $ret = app('Authorize')->getJsApiTicket($app_id);
        if($ret['state']){
            return ['code'=>"200",'data'=>[
                'js_api_ticket'=>$ret['data']['js_api_ticket'],
                'js_api_ticket_expire_time'=>$ret['data']['js_api_ticket_expire_time']
            ]];
        }else{
            return ['code'=>"400",'msg'=>$ret['msg']];
        }
    }


    public function create()
    {
        $app_id = request()->input('app_id');
        $redirect_url = request()->input('redirect_url');
        $authorize_id = request()->input('authorize_id');
        $scope = request()->input('scope');
        $ret = app('Authorize')->create($app_id,$redirect_url,$authorize_id,$scope);
        if($ret['state']){
            return ['code'=>"200",'wx_state'=>$ret['wx_state']];
        }else{
            return ['code'=>"400",'msg'=>$ret['msg']];
        }
    }

    public function redirect($state)
    {
        $ret = app('Authorize')->getRedirect($state);
        if($ret['state']){
            return Redirect()->to($ret['url']);
        }else{
            return response('File not found', 404)->header('Content-Type', 'text/plain');
        }
    }

    public function redirectBack()
    {
       if(isset($_GET['code']) && isset($_GET['state'])){
           $url = app('Authorize')->redirectBack($_GET['state'],$_GET['code']);
           return Redirect()->to($url);
       }else{
           return response('File not found.', 404)->header('Content-Type', 'text/plain');
       }
    }

}
