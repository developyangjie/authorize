<?php
/**
 * Created by PhpStorm.
 * User  :  刘磊
 * Date  :  2018/3/8
 * Time  :  13:58
 * Email :  369968620@163.com
 */
namespace App\Services;

use Illuminate\Support\Facades\DB;

class Sms {
    public function smsSend($data)
    {
        $check = array_map('self::is_mobile',$data);
        $search = array_search(false,$check);
        $ret_data = [];
        foreach($data as $k=>$v){
            if(is_mobile($v['accept'])){
                $sql = "insert into push_msg (msg_type,accept,content,create_time) value (?,?,?,?) ";
                $bind = ['1',$v['accept'],$v['content'],time()];
                DB::insert($sql,$bind);
                $ret_data[$v['custom_id']]['code'] = "200";
                $ret_data[$v['custom_id']]['msg_id'] = DB::getPdo()->lastInsertId();
            }else{
                $ret_data[$v['custom_id']]['code'] = "400";

                $ret_data[$v['custom_id']]['msg'] = '短信接收者号码错误';
            }
        }
        return $ret_data;
    }

    public function smsListenLy($data)
    {
        $sql = "select msg_id from push_msg where request_id = ?";
        $row = DB::selectOne($sql,[$data['msgid']]);
        $sql = "update push_msg set state = ?,errmsg = ?,receipt_time = ? where msg_id = ?";
        if(isset($data['status']) && $data['status'] == "DELIVRD"){
            $bind = ['2',null,time(),$row['msg_id']];
        }else{
            $bind = ['4',$data['status'],time(),null];
        }
        DB::update($sql,$bind);
    }

    public function is_mobile($sms)
    {
        if(isset($sms['accept']) && preg_match("/^1[3456789]{1}\d{9}$/",$sms['accept'])){
            return true;
        }else{
            return false;
        }
    }


}