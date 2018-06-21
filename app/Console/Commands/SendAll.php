<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时群发图文';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $before_time = time()-60;
        $after_time = $before_time+120;
        $query = "select * from `send_task` WHERE is_delete= 0 and `status`= 0 and send_time BETWEEN ? AND ? limit 20";
        $res = DB::select($query,[$before_time,$after_time]);
        foreach($res as $vo){
            $thumb_url_str = $vo['thumb_url_str'];
            $url_str = $vo['url_str'];
            $title_str = $vo['title_str'];
            $app_id = $vo['app_id'];
            $media_id = $vo['media_id'];
            $wx_res = app('Wx')->getWx($app_id);
            if($wx_res['state']){
                $wx_id = $wx_res['data']['wx_id'];
                $authorization_code = $wx_res['auth_code'];
                $component_appid = env('APP_XUET_ID');
                $component_secret = env('APP_XUET_SECRET');
                $token_res = app('Wx')->getAuthToken($wx_id,$component_appid,$component_secret,$authorization_code);
                if($token_res['code']==200){
                    $access_token = $token_res['AuthorizerAccessToken'];
                    $send_res = app('JSSDK')->sendallNews($access_token,$media_id);
                    $res_arr = json_decode($send_res,True);
                    if($res_arr['errcode']==0){
                        $update_status = "update send_task set `status`=1 WHERE id= ? ";
                        //群发成功更新状态
                        DB::update($update_status,[$vo['id']]);
                        $msg_data_id = $res_arr['msg_data_id'];
                        $msg_id = $res_arr['msg_id'];
                        app('Material')->addHistoryMsg($app_id,$msg_id,$msg_data_id,$media_id,$thumb_url_str,$url_str,$title_str);
                    }
                }
            }
        }
    }
}
