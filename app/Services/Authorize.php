<?php
/**
 * Created by PhpStorm.
 * User  :  刘磊
 * Date  :  2018/1/28
 * Time  :  16:58
 * Email :  369968620@163.com
 */
namespace App\Services;

use Illuminate\Support\Facades\DB;

class Authorize
{
    public function getJsApiTicket($app_id)
    {
        if(isset($app_id) && !empty($app_id)){
            $sql = "select wx_id,app_id,app_secret,access_token,access_token_expire_time,js_api_ticket,js_api_ticket_expire_time from wechat_app where app_id = ?";
            $row = DB::selectOne($sql,[$app_id]);
            if( (isset($row['js_api_ticket']) &&   isset($row['js_api_ticket_expire_time']) && ($row['js_api_ticket_expire_time'] - 1000 > time()))){
                return ['state'=>true,'data'=>$row];
            }else{
                $ret = $this->getAccessToken($app_id);
                $access_token = $ret['data']['access_token'];
                $data = app('JSSDK')->init($row['app_id'],$row['app_secret'])->getJsApiTicket($access_token);
                if($data['state']){
                    if(isset($data['data']['ticket'])){
                        $ret = ['state'=>true,'data'=>['js_api_ticket'=>$data['data']['ticket'],'js_api_ticket_expire_time'=>time()+$data['data']['expires_in']-200]];
                        $sql = "update wechat_app set js_api_ticket = ? ,js_api_ticket_expire_time = ? where app_id = ?";
                        DB::update($sql,[$ret['data']['js_api_ticket'],$ret['data']['js_api_ticket_expire_time'],$row['app_id']]);
                    }else{
                        $ret = ['state'=>false,'msg'=>$data['errmsg']];
                    }
                }else{
                    $ret = ['state'=>false,'msg'=>$data['msg']];
                }
                return $ret;
            }
        }else{
            return ['state'=>false,'msg'=>'错误的app_id'];
        }
    }


    public function getAccessToken($app_id)
    {
        if(isset($app_id) && !empty($app_id)){
            $sql = "select wx_id,app_id,app_secret,access_token,access_token_expire_time from wechat_app where app_id = ?";
            $row = DB::selectOne($sql,[$app_id]);
            if( (isset($row['access_token']) &&   isset($row['access_token_expire_time']) && ($row['access_token_expire_time'] - 1000 > time()))){
                return ['state'=>true,'data'=>$row];
            }else{
                $data = app('JSSDK')->init($row['app_id'],$row['app_secret'])->getAccessToken();
                if($data['state']){
                    if(isset($data['data']['access_token'])){
                        $ret = ['state'=>true,'data'=>['access_token'=>$data['data']['access_token'],'access_token_expire_time'=>time()+$data['data']['expires_in']-200]];
                        $sql = "update wechat_app set access_token = ? ,access_token_expire_time = ? where app_id = ?";
                        DB::update($sql,[$ret['data']['access_token'],$ret['data']['access_token_expire_time'],$row['app_id']]);
                    }else{
                        $ret = ['state'=>false,'msg'=>$data['data']['errmsg']];
                    }
                }else{
                    $ret = ['state'=>false,'msg'=>$data['msg']];
                }
                return $ret;
            }
        }else{
            return ['state'=>false,'msg'=>'错误的app_id'];
        }
    }

    public function create($app_id,$redirect_url,$authorize_id,$scope)
    {
        if(isset($authorize_id) && !empty($authorize_id)){
//            if(filter_var(base64_decode($redirect_url), FILTER_VALIDATE_URL)){
                $sql = "select wx_id,app_id,app_secret,access_token,access_token_expire_time from wechat_app where app_id = ?";
                $row = DB::selectOne($sql,[$app_id]);
                if(isset($row) && !empty($row)){
                    if(empty($scope)){
                        $scope = "0";
                    }

                    $sql = "insert into authorize_redirect (app_id,wx_state,created_at,expire_time,redirect_url,authorize_id,scope) value
                                                (?,?,?,?,?,?,?)";
                    $created_at = time();

                    $wx_state = get_randChar(100);

                    DB::insert($sql,[$app_id,$wx_state,$created_at,$created_at+1800,$redirect_url,$authorize_id,$scope]);

                    $id = DB::getPdo()->lastInsertId();

                    return ['state'=>true,'wx_state'=>$wx_state];
                }else{
                    return ['state'=>false,'msg'=>'未找到授权应用'];
                }
//            }else{
//                return ['state'=>false,'msg'=>'错误的redirect_url'];
//            }
        }else{
            return ['state'=>false,'msg'=>'错误的authorize_id'];
        }

    }

    public function getRedirect($wx_state)
    {
        $sql = "select a.wx_state,a.expire_time,a.scope,b.app_id,b.app_secret from authorize_redirect a left join wechat_app b on a.app_id = b.app_id  where a.wx_state =  ? order by a.redirect_id desc";
        $row = DB::selectOne($sql,[$wx_state]);
        if(isset($row) && !empty($row)){
            if($row['expire_time'] >= time()){
                $url = app('JSSDK')->init($row['app_id'],$row['app_secret'])->getCodeUrl($wx_state,env('APP_URL'),(string)$row['scope']);
                return ['state'=>true,'url'=>$url];
            }else{
                return ['state'=>false,'msg'=>'授权请求已过期!'];
            }
        }else{
            return ['state'=>false,'msg'=>'非法请求'];
        }
    }

    public function redirectBack($wx_state,$code)
    {
        $sql = "select redirect_url from authorize_redirect where wx_state = ? ";
        $row = DB::selectOne($sql,[$wx_state]);
        $redirect_url = base64_decode($row['redirect_url']);
        $pos = strpos($redirect_url, '?');
        if ($pos === false) {
            $url = $redirect_url.'?'.'code='.$code;
        } else {
            $url = $redirect_url.'&'.'code='.$code;
        }
        return $url;
    }

}