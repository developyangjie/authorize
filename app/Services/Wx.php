<?php
/**
 * Created by PhpStorm.
 * User  :  刘磊
 * Date  :  2018/1/28
 * Time  :  16:58
 * Email :  369968620@163.com
 */
namespace App\Services;

use App\Http\Lib\WX\DecryptMsg\Prpcrypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class Wx
{
    public function setWx($org_name,$app_id,$app_secret,$auth_file_name,$auth_file_data)
    {
        $check_state = true;
        if(isset($auth_file_name)){
            $info = pathinfo($auth_file_name);
            if($info['extension'] != 'txt'){
                $check_state = false;
            }
        }
        if($check_state){
            $sql = "select wx_id,auth_file_name from wechat_app where app_id = ?";
            $row = DB::selectOne($sql,[$app_id]);
            if(isset($row) && !empty($row)){
                $sql = "update wechat_app set ";
                $sql .= " updated_at = ?";
                $bind[] = time();
                if(isset($org_name) && !empty($org_name)){
                    $sql .= " ,org_name = ?";
                    $bind[] = $org_name;
                }
                if(isset($app_secret) && !empty($app_secret)){
                    $sql .= " ,app_secret = ?";
                    $bind[] = $app_secret;
                }
                if(isset($auth_file_name) && !empty($auth_file_name)){
                    $sql .= " ,auth_file_name = ?";
                    $bind[] = $auth_file_name;

                    if(isset($row['auth_file_name'])){
                        Storage::disk('root_public')->delete($row['auth_file_name']);
                    }
                    Storage::disk('root_public')->put($auth_file_name, $auth_file_data);
                }
                $sql .= " where app_id = ?";
                $bind[] = $app_id;
                DB::update($sql,$bind);
            }else{
                $sql = "insert into wechat_app (org_name,app_id,app_secret,auth_file_name,created_at,updated_at) value (?,?,?,?,?,?)";
                $time = time();
                DB::insert($sql,[$org_name,$app_id,$app_secret,$auth_file_name,$time,$time]);
                Storage::disk('root_public')->put($auth_file_name, $auth_file_data);
            }
            return ['state'=>true];

        }else{
            return ['state'=>false,'msg'=>'授权验证文件格式错误!'];
        }
    }

    public function getWx($app_id)
    {
        $sql = "select * from wechat_app where app_id = ?";
        $row = DB::selectOne($sql,[$app_id]);
        if(isset($row) && count($row) > 0){
            return ['state'=>true,'data'=>$row];
        }else{
            return ['state'=>false,'msg'=>'微信公众号不存在!'];
        }
    }

    public function wxSend($msgList)
    {
        $ret_data = [];
        foreach($msgList as $v){
            $sql = "insert into push_msg (msg_type,wx_template_id,accept,sender,content,create_time) value
            (?,?,?,?,?,?)";
            $sender = $v['sender'];
            $accept = $v['accept'];
            $wx_template_id = $v['wx_template_id'];
            unset($v['sender']);
            unset($v['accept']);
            unset($v['wx_template_id']);
            $content = base64_encode(json_encode($v));
            DB::insert($sql,['3',$wx_template_id,$accept,$sender,$content,time()]);
            $ret_data[$v['custom_id']]['code'] = "200";
            $ret_data[$v['custom_id']]['msg_id'] = DB::getPdo()->lastInsertId();
        }
        return $ret_data;
    }

    //获取最新component_verify_ticket
    public function getComponentVerifyTicket($app_id){
        $query = "select component_verify_ticket from component_info where app_id=?";
        $res = DB::selectOne($query,[$app_id]);
        if($res && $res['component_verify_ticket']){
            return [
                'code'=>200,
                'ComponentVerifyTicket'=>$res['component_verify_ticket']
            ];
        }else{
            return [
                'code'=>400,
                'msg'=>'未找到app_id或者ComponentVerifyTicket不存在'
            ];
        }
    }
    //获取component_access_token
    public function getComponentAccessToken($app_id,$app_secret){
        $query = "select * from `component_info` WHERE app_id=?";
        $res = DB::selectOne($query,[$app_id]);
        if($res){
            if($res['access_token_update_time']+$res['access_token_expires_in']-600>time()){
                return [
                    'code'=>200,
                    'ComponentAccessToken'=>$res['component_access_token']
                ];
            }
        }
        $res = $this->getComponentVerifyTicket($app_id);
        if($res['code']==200) {
            $component_verify_ticket = $res['ComponentVerifyTicket'];
            $res = app('JSSDK')->getComponentAccessToken($app_id, $app_secret, $component_verify_ticket);
            $res_arr = json_decode($res,TRUE);
            if (isset($res_arr['component_access_token']) && !empty($res_arr['component_access_token'])) {
                $component_access_token = $res_arr['component_access_token'];
                $expires_in = $res_arr['expires_in'];
                $query = "update `component_info` set component_access_token=?,access_token_update_time=?,access_token_expires_in=? WHERE app_id=?";
                DB::update($query,[$component_access_token,time(),$expires_in,$app_id]);
                return [
                    'code' => 200,
                    'ComponentAccessToken' => $component_access_token
                ];
            }else{
                return [
                    'code'=>400,
                    'msg'=>'获取ComponentAccessToken失败,错误码：'.$res_arr['errcode']
                ];
            }
        }else{
            return $res;
        }
    }
    //获取预授权码
    public function getPreAuthCode($app_id,$app_secret){
        $query = "select * from `component_info` WHERE app_id=?";
        $res = DB::selectOne($query,[$app_id]);
        if($res){
            if($res['pre_code_update_time']+$res['pre_code_expires_in']-60>time()){
                return [
                    'code'=>200,
                    'PreAuthCode'=>$res['pre_auth_code']
                ];
            }
        }
        $res = $this->getComponentAccessToken($app_id,$app_secret);
        if($res['code']==200){
            $component_access_token = $res['ComponentAccessToken'];
            $res = app('JSSDK')->getPreAuthCode($app_id,$component_access_token);
            $res_arr = json_decode($res,TRUE);
            if (isset($res_arr['pre_auth_code']) && !empty($res_arr['pre_auth_code'])) {
                $pre_auth_code = $res_arr['pre_auth_code'];
                $expires_in = $res_arr['expires_in'];
                $query = "update `component_info` set pre_auth_code=?,pre_code_update_time=?,pre_code_expires_in=? WHERE app_id=?";
                DB::update($query,[$pre_auth_code,time(),$expires_in,$app_id]);
                return [
                    'code' => 200,
                    'PreAuthCode' => $pre_auth_code
                ];
            }else{
                return [
                    'code'=>400,
                    'msg'=>'获取PreAuthCode失败,错误码：'.$res_arr['errcode']
                ];
            }
        }else{
            return $res;
        }
    }
    //更新授权码
    public function addAuthCode($wx_id,$auth_code,$auth_expires_in){
        $query = "update `wechat_app` set `auth_code`=?,`auth_expires_in`=?,auth_update_time=? WHERE `wx_id`=?";
        $res = DB::update($query,[$auth_code,$auth_expires_in,time(),$wx_id]);
        if($res>0){
            return [
                'code' => 200,
                'msg' => '更新授权码成功'
            ];
        }else{
            return [
                'code' => 400,
                'msg' => '更新授权码失败'
            ];
        }
    }
    //获取授权token
    public function getAuthToken($wx_id,$component_appid,$component_secret,$authorization_code){
        //获取授权码
        $query ="select * from wechat_app WHERE wx_id=?";
        $res = DB::selectOne($query,[$wx_id]);
        if($res){
            $res_component = $this->getComponentAccessToken($component_appid,$component_secret);
            if($res_component['code']==200) {
                $component_access_token = $res_component['ComponentAccessToken'];
                if ($res['authorizer_access_token']) {
                    //有并且没有过期
                    if ($res['authorizer_token_expires_in'] + $res['authorizer_update_time'] - 600 > time()) {
                        return [
                            'code' => 200,
                            'AuthorizerAccessToken' => $res['authorizer_access_token']
                        ];
                    } else {
                        //刷新令牌
                        $auth_app_id = $res['app_id'];
                        $authorizer_refresh_token = $res['authorizer_refresh_token'];
                        $res1 = app('JSSDK')->refreshAuthToken($component_appid, $component_access_token, $auth_app_id, $authorizer_refresh_token);
                        $res1_arr = json_decode($res1, TRUE);
                        if (isset($res1_arr['authorizer_access_token']) && !empty($res1_arr['authorizer_access_token'])) {
                            $authorizer_access_token = $res1_arr['authorizer_access_token'];
                            $expires_in = $res1_arr['expires_in'];
                            $authorizer_refresh_token = $res1_arr['authorizer_refresh_token'];
                            $query = "update `wechat_app` set authorizer_access_token=?,authorizer_refresh_token=?,authorizer_token_expires_in=?,authorizer_update_time=? WHERE wx_id=?";
                            DB::update($query, [$authorizer_access_token, $authorizer_refresh_token, $expires_in, time(), $wx_id]);
                            return [
                                'code' => 200,
                                'AuthorizerAccessToken' => $authorizer_access_token
                            ];
                        } else {
                            return [
                                'code' => 400,
                                'msg' => '获取AuthorizerAccessToken失败,错误码：' . $res1_arr['errcode']
                            ];
                        }
                    }
                } else {
                    //没有就直接获取
                    $auth_expires_in = $res['auth_expires_in'];
                    $auth_update_time = $res['auth_update_time'];
                    if ($auth_update_time + $auth_expires_in - 5 > time()) {
                        $res2 = app('JSSDK')->getAuthToken($component_appid, $component_access_token, $authorization_code);
                        $res2_arr = json_decode($res2, TRUE);
                        if (isset($res2_arr['authorization_info']['authorizer_access_token']) && !empty($res2_arr['authorization_info']['authorizer_access_token'])) {
                            $authorizer_access_token = $res2_arr['authorization_info']['authorizer_access_token'];
                            $expires_in = $res2_arr['authorization_info']['expires_in'];
                            $authorizer_appid = $res2_arr['authorization_info']['authorizer_appid'];
                            $authorizer_refresh_token = $res2_arr['authorization_info']['authorizer_refresh_token'];
                            $func_info = $res2_arr['authorization_info']['func_info'];
                            $func_info_ids = '';
                            foreach ($func_info as $vo) {
                                $func_info_ids .= $vo['funcscope_category']['id'] . ',';
                            }
                            $query = "update `wechat_app` set authorizer_access_token=?,authorizer_refresh_token=?,authorizer_token_expires_in=?,authorizer_update_time=?,app_id=?,func_info_ids=? WHERE wx_id=?";
                            DB::update($query, [$authorizer_access_token, $authorizer_refresh_token, $expires_in, time(), $authorizer_appid, $func_info_ids, $wx_id]);
                            return [
                                'code' => 200,
                                'AuthorizerAccessToken' => $authorizer_access_token
                            ];
                        } else {
                            return [
                                'code' => 400,
                                'msg' => '获取AuthorizerAccessToken失败,错误码：' . $res2_arr['errcode']
                            ];
                        }
                    } else {
                        return [
                            'code' => 400,
                            'msg' => '授权码已过期请重新授权'
                        ];
                    }
                }
            }else{
                return $res_component;
            }
        }else{
            return [
                'code' => 400,
                'msg' => '未找到app_id'
            ];
        }
    }
    //获取第三方公众号信息
    public function getAppInfo($app_id,$app_secret){
        $component_appid = env('APP_XUET_ID');
        $component_secret = env('APP_XUET_SECRET');
        $res = $this->getComponentAccessToken($component_appid,$component_secret);
        if($res['code']==200){
            $component_access_token = $res['ComponentAccessToken'];
            $result = app('JSSDK')->getAuthorizerInfo($component_access_token,$component_appid,$app_id);
            $result_arr = json_decode($result,True);
            if(array_key_exists('errcode',$result_arr) && $result_arr['errcode']!=0){
                return [
                    'code' => 400,
                    'msg'  => '获取公众号信息错误，错误码：'.$result_arr['errcode']
                ];
            }else{
                return [
                    'code' => 200,
                    'msg'  => $result_arr
                ];
            }
        }
        return $res;
    }
    //获取公众号自定义菜单
    public function getMenuList($wx_id,$component_appid,$component_secret,$authorization_code){
        $res= $this->getAuthToken($wx_id,$component_appid,$component_secret,$authorization_code);
        if($res['code']==200) {
            $access_token = $res['AuthorizerAccessToken'];
            $res1 = app('JSSDK')->getMenuList($access_token);
            $res1_arr = json_decode($res1, TRUE);
            if (array_key_exists('errcode', $res1_arr)) {
                return [
                    'code' => 400,
                    'msg' => '获取自定义菜单失败,错误码：' . $res1_arr['errcode']
                ];
            } else {
                return $res1_arr;
            }
        }else{
            return $res;
        }
    }
    //创建公众号自定义菜单
    public function createMenuList($wx_id,$component_appid,$component_secret,$authorization_code,$data_json){
        $res= $this->getAuthToken($wx_id,$component_appid,$component_secret,$authorization_code);
        if($res['code']==200) {
            $access_token = $res['AuthorizerAccessToken'];
            $res1 = app('JSSDK')->createMenuList($access_token,$data_json);
            $res1_arr = json_decode($res1, TRUE);
            if (array_key_exists('errcode', $res1_arr) && $res1_arr['errcode']!=0) {
                return [
                    'code' => 400,
                    'msg' => '创建自定义菜单失败,错误码：' . $res1_arr['errcode'].'错误信息：'.$res1_arr['errmsg']
                ];
            } else {
                return [
                    'code' => 400,
                    'msg' => '创建自定义菜单成功'
                ];
            }
        }else{
            return $res;
        }
    }
    //获取key对应的文本
    public function getTextByKey($key,$app_id){
        $reply_query = "select * from event_text_reply WHERE `key`=? and app_id=?";
        $res = DB::selectOne($reply_query,[$key,$app_id]);
        if($res){
            return [
                'code'=>200,
                'msg'=>$res['reply_text'],
                'id'=>$res['id']
            ];
        }else{
            return [
                'code'=>400,
                'msg'=>'未找到key'
            ];
        }
    }
    //插入key—text对应值
    public function addKeyText($key,$text,$app_id){
        $res = $this->getTextByKey($key,$app_id);
        if($res['code']==200){
            $query = "update `event_text_reply` set `reply_text`=? WHERE id=?";
            $up_res = DB::update($query,[$text,$res['id']]);
            if($up_res!==false){
                return [
                    'code'=>200,
                    'msg'=>'更新成功'
                ];
            }else{
                return [
                    'code'=>400,
                    'msg'=>'key和text值添加成功'
                ];
            }
        }else{
            $query = "insert into `event_text_reply` VALUES ('',?,?,?)";
            DB::insert($query,[$key,$text,$app_id]);
            return [
                'code'=>200,
                'msg'=>'更新成功'
            ];
        }
    }
    //添加回复
    public function addReply($key_word,$match_type,$reply_id_str,$app_id){
        $query = "insert into `text_receive` VALUES ('',?,?,?,?,0)";
        DB::insert($query,[$app_id,$key_word,$match_type,$reply_id_str]);
        return [
            'code'=>200,
            'msg'=>'添加成功'
        ];
    }
    //更新回复
    public function updateReply($key_word,$match_type,$reply_id_str,$id){
        $query = "update `text_receive` set `key_word`=?,`match_type`=?,`reply_id_str`=? WHERE id=?";
        $res = DB::update($query,[$key_word,$match_type,$reply_id_str,$id]);
        if($res!==false){
            return [
                'code'=>200,
                'msg'=>'更新成功'
            ];
        }
    }
    //新增文字回复素材
    public function addTextMaterial($app_id,$reply_text){
        $query = "insert into `text_reply` VALUES ('',?,?)";
        DB::insert($query,[$app_id,$reply_text]);
        $text_id = DB::getPdo()->lastInsertId();
        return [
            'code'=>200,
            'msg'=>'添加成功',
            'text_id'=>$text_id
        ];
    }
    //新增图文回复素材
    public function addNewsMaterial($app_id,$pic_url,$title,$description,$url){
        $query = "insert into `news_reply` VALUES ('',?,?,?,?,?,?)";
        DB::insert($query,[$app_id,$pic_url,$title,$description,$url,time()]);
        $news_id = DB::getPdo()->lastInsertId();
        return [
            'code'=>200,
            'msg'=>'添加成功',
            'news_id'=>$news_id
        ];
    }
    //编辑文字回复素材
    public function editTextMaterial($id,$reply_text){
        $query = "update `text_reply` set `reply_text`=? WHERE id=?";
        $res = DB::update($query,[$reply_text,$id]);
        if($res!==false){
            return [
                'code'=>200,
                'msg'=>'更新成功'
            ];
        }
    }
    //编辑图文回复素材
    public function editNewsMaterial($id,$pic_url,$title,$description,$url){
        $query = "update `news_reply` set `pic_url`=?,`title`=?,`description`=?,`url`=? WHERE `id`=?";
        $res = DB::update($query,[$pic_url,$title,$description,$url,$id]);
        if($res!==false){
            return [
                'code'=>200,
                'msg'=>'更新成功',
            ];
        }else{
            return [
                'code'=>400,
                'msg'=>'更新失败',
            ];
        }
    }
    //获取回复规则列表
    public function getReplyList($app_id){
        $query = "select * from `text_receive` WHERE is_delete=0 and app_id=?";
        $res = DB::select($query,[$app_id]);
        foreach ($res as $key=>$vo){
            $reply_id_str = $vo['reply_id_str'];
            $reply_id_arr = explode(',',$reply_id_str);
            if(count($reply_id_arr)>1){
                $res[$key]['reply_content'] = '多种回复';
            }else{
                $arr = explode('|',$reply_id_str);
                $reply_type = $arr[0];
                $reply_id = $arr[1];
                switch ($reply_type){
                    case 'text':
                        $query = "select `reply_text` from text_reply where id=?";
                        $text_res = DB::selectOne($query,[$reply_id]);
                        $res[$key]['reply_content'] = '文字|'.$text_res['reply_text'];
                        break;
                    case 'image':
                        $res[$key]['reply_content'] = '图片';
                        break;
                    case 'voice':
                        $res[$key]['reply_content'] = '语音';
                        break;
                    case 'video':
                        $res[$key]['reply_content'] = '视频';
                        break;
                    case 'music':
                        $res[$key]['reply_content'] = '音乐';
                        break;
                    case 'news':
                        $res[$key]['reply_content'] = '图文';
                        break;
                }
            }
        }
        return $res;
    }
    //删除回复规则
    public function delReply($id){
        $query = "update `text_receive` set is_delete=1 WHERE id=?";
        $res = DB::update($query,[$id]);
        if($res!==false){
            return [
                'code'=>200,
                'msg'=>'删除成功'
            ];
        }else{
            return [
                'code'=>400,
                'msg'=>'删除失败'
            ];
        }
    }
    //获取规则详情
    public function getReplyInfo($id){
        $query = 'select * from `text_receive` WHERE id=?';
        $res = DB::selectOne($query,[$id]);
        if($res){
            $reply_id_str = $res['reply_id_str'];
            $reply_id_arr = explode(',',$reply_id_str);
            foreach ($reply_id_arr as $vo){
                $arr = explode('|',$vo);
                $reply_type = $arr[0];
                $id_str = $arr[1];
//                DB::connection()->enableQueryLog();
                switch ($reply_type){
                    case 'text':
                        $type = '文字';
                        $query = "select * from `text_reply` where id=?";
                        $result = DB::selectOne($query,[$id_str]);
                        break;
                    case 'news':
                        $type = '图文';
                        $id_arr = explode(';',$id_str);
                        $id_str = '';
                        foreach ($id_arr as $vo){
                            $id_str .= $vo.',';
                        }
                        $id_str = rtrim($id_str,',');
                        $query = "select * from `news_reply` where id in ($id_str)";
                        $result = DB::select($query);
                        break;
                }
//                dd(DB::getQueryLog());
                $data['reply_type'] = $reply_type;
                $data['reply_type_words'] = $type;
                $data['item'] = $result;
                $res['reply_content'][] = $data;
            }
            return $res;
        }else{
            return [
                'code'=>400,
                'msg'=>'未找到规则'
            ];
        }
    }
    //获取所有回复
    public function getAllReply($reply_type,$app_id,$start=0,$length=0){
        $limit = '';
        $arr = [$app_id];
        if($length>0){
            $limit = " limit ?,?";
            $arr[] = $start;
            $arr[] = $length;
        }
        switch ($reply_type){
            case 'text':
                $table = '`text_reply`';
                break;
            case 'news':
                $table = '`news_reply`';
                break;
        }
        $query = "select * from $table WHERE `app_id`=? ORDER BY `id` desc$limit";
        $res = DB::select($query,$arr);
        return $res;
    }
    //获取回复详情
    public function getReplyDetailById($reply_type,$id){
        switch ($reply_type){
            case 'text':
                $table = '`text_reply`';
                break;
            case 'news':
                $table = '`news_reply`';
                break;
        }
        $query = "select * from $table WHERE `id`=?";
        $res = DB::selectOne($query,[$id]);
        return $res;
    }
}