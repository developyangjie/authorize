<?php
/**
 * Created by PhpStorm.
 * User  :  沈强
 * Date  :  2018/5/21
 * Time  :  16:50
 * Email :  736025986@qq.com
 */

namespace App\Services;


use Illuminate\Support\Facades\DB;

class Fans
{
    public function addFansRecord($openID,$app_id,$subscribe)
    {
        $bind_arr = [$openID,$app_id,$subscribe,time()];
        $query = "insert into `fans_record` VALUES ('',?,?,?,?)";
        DB::insert($query,$bind_arr);
    }
    //获取总粉丝输
    public function getTotalFansNum($access_token)
    {
        $res = app('JSSDK')->getUsers($access_token);
        $arr = json_decode($res,true);
        if(array_key_exists('errcode',$arr) && $arr['errcode']!=0){
            return [
                'code'=> 400,
                'msg' => '获取粉丝数失败错误码：'.$arr['errcode'].'，错误信息为：'.$arr['errmsg']
            ];
        }
        $total_nums = $arr['total'];
        return [
            'code'=> 200,
            'msg' => $total_nums
        ];
    }
    //获取新增人数
    public function getAddFans($app_id,$time1,$time2)
    {
        //新关注的粉丝
        $query = "select count(*) num from `fans_record` WHERE `app_id`=? and `action`=1 AND `create_time` BETWEEN ? and ? ";
        $res = DB::selectOne($query,[$app_id,$time2,$time1]);
        $new_subscribe_num = $res['num'];
        //新取消的人数
        $query = "select count(*) num from `fans_record` WHERE `app_id`=? and `action`=0 AND `create_time` BETWEEN ? and ? ";
        $res = DB::selectOne($query,[$app_id,$time2,$time1]);
        $new_unsubscribe_num = $res['num'];
        $add_fans_num = $new_subscribe_num-$new_unsubscribe_num;
        return [
            'new_subscribe_num' => $new_subscribe_num,
            'new_unsubscribe_num' => $new_unsubscribe_num,
            'add_fans_num' => $add_fans_num,
        ];
    }
}