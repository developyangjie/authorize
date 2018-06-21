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

class Msg {
    public function getMsgState($msg_id)
    {
        $sql = "select msg_id,state,errmsg,receipt_time from push_msg where msg_id in ";
        $bind = [];
        foreach($msg_id as $k=>$v){
            if($k == 0){
                $sql .= '(?';

            }else{
                $sql .= ',?';
            }
            $bind[] = $v;
        }
        $sql .= ')';
        $rs = DB::select($sql,$bind);
        return $rs;
    }
}