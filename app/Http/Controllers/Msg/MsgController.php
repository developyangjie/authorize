<?php
/**
 * Created by PhpStorm.
 * User  :  刘磊
 * Date  :  2018/3/8
 * Time  :  20:55
 * Email :  369968620@163.com
 */
namespace App\Http\Controllers\Msg;

use App\Http\Controllers\Controller;
use Request;


class MsgController {

    public function getMsgState()
    {
        $msg_str = Request::input('msg_id');
        $msg_data = explode(',',$msg_str);
        $msg_id = (array_filter(array_unique($msg_data),'self::issnum'));
        $ret = app('Msg')->getMsgState($msg_id);
        return ['code'=>'200','data'=>$ret];
    }

    public function issnum($num)
    {
        if(mb_strlen($num) > 0 && floor($num) == $num){
            return true;
        }else{
            return false;
        }
    }
}
