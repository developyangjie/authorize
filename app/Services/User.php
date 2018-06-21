<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * 登录
     * @param $username  账号
     * @param $data  密码
     * @return mixed
     */
    public function login($username,$password){
        $sql = "select * from wechat_app where user_name = ?";
        $userInfo = DB::selectOne($sql,[$username]);
        if($userInfo){
            if(!password_verify($password,$userInfo['password'])){
                return ['code' => '400', 'msg' => '密码错误'];
            }else{
                unset($userInfo['password']);
                return ['code' => '200', 'msg' => '登录成功', 'data' => $userInfo];
            }
        }else{
            return ['state'=>false,'code' => '403', 'msg' => '账号不存在'];
        }
    }
    //获取公众号信息
    public function getAppInfo($wx_id){
        $sql = "select * from wechat_app where wx_id = ?";
        $userInfo = DB::selectOne($sql,[$wx_id]);
        return $userInfo;
    }
    //刷新授权方的帐号基本信息（入库）
    public function flashAppInfo($wx_id,$app_id,$app_secret){
        $res = app('Wx')->getAppInfo($app_id,$app_secret);
        if($res['code']==200){
            $data = $res['msg'];
            $nick_name = $data['authorizer_info']['nick_name'];
            $head_img = $data['authorizer_info']['head_img'];
            $service_type = $data['authorizer_info']['service_type_info']['id'];
            $verify_type = $data['authorizer_info']['verify_type_info']['id'];
            $wx_no = $data['authorizer_info']['user_name'];
            $principal_name = $data['authorizer_info']['principal_name'];
            $alias = $data['authorizer_info']['alias'];
            $qrcode_url = $data['authorizer_info']['qrcode_url'];
            $query = "update `wechat_app` set `nick_name`=?,`head_img`=?,`service_type`=?,`verify_type`=?,`wx_no`=?,`principal_name`=?,`alias`=?,`qrcode_url`=? WHERE `wx_id`=?";
            $up_res = DB::update($query,[$nick_name,$head_img,$service_type,$verify_type,$wx_no,$principal_name,$alias,$qrcode_url,$wx_id]);
            if($up_res!==false){
                return [
                    'code' => 200,
                    'msg' => '更新成功'
                ];
            }else{
                return [
                    'code' => 400,
                    'msg' => '更新失败'
                ];
            }
        }
        return $res;
    }
    //添加过期时间
    public function updateExpireTime($expire_time,$wx_id)
    {
        $query = "update `wechat_app` set `auth_expire_in` = ? WHERE `wx_id`=?";
        $res = DB::update($query,[$expire_time,$wx_id]);
        if($res!==false){
            return [
                'code' => 200,
                'msg' => '更新成功'
            ];
        }else{
            return [
                'code' => 400,
                'msg' => '更新失败'
            ];
        }
    }
    public function updateUserPwd($password,$wx_id){
        $hash_passord = password_hash($password, PASSWORD_BCRYPT);
        $query = "update wechat_app set password = ? where wx_id = ?";
        $res = DB::update($query,[$hash_passord,$wx_id]);
        if($res){
            return ['code' => 200, 'msg' => '修改成功'];
        }else{
            return ['code' => 400, 'msg' => '修改失败'];
        }
    }
}