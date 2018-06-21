<?php
/**
 * Created by PhpStorm.
 * User  :  刘磊
 * Date  :  2018/3/5
 * Time  :  17:46
 * Email :  369968620@163.com
 */
namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use DOMDocument;

class WxListenController extends Controller{
    public function eventListen(Request $request,$app_id)
    {
        echo 'success';
        $notice_data = $request->getContent();
        $timeStamp  = $request->query('timestamp');
        $nonce = $request->query('nonce');
        $msg_sign  = $request->query('msg_signature');
        $encrypt_type = $request->query('encrypt_type');
        $res = app('DecryptXml')->decrypt($notice_data,$msg_sign,$timeStamp,$nonce);
        $log = new Logger('db');
        $log->pushHandler(
            new StreamHandler(
                storage_path('logs/wxPush/'.date('Ymd').'.log'),
                Logger::INFO
            )
        );
        $url = url()->full();
        $log->addInfo($notice_data.$url);
        if($res['code']==200){
            $res = app('WxListen')->addTicket($res['msg']);
        }
        return $res;
    }
    //授权码回调地址
    public function getAuthCode(Request $request){
        $auth_code  = $request->query('auth_code');
        $auth_expires_in = $request->query('expires_in');
        $wx_id = session('admin_user_info.wx_id');
        $app_id = session('admin_user_info.app_id');
        $app_secret = session('admin_user_info.app_secret');
        $res = app('Wx')->addAuthCode($wx_id,$auth_code,$auth_expires_in);
        if($res['code']==200){
            //获取用户信息
            app('User')->flashAppInfo($wx_id,$app_id,$app_secret);
            $user_info = app('User')->getAppInfo($wx_id);
            unset($user_info['password']);
            session(['admin_user_info'=>$user_info]);
        }
        return redirect()->route('adminIndex',['message'=>$res['msg']]);
    }
    //消息事件监听
    public function listen(Request $request,$app_id)
    {
        $notice_data = $request->getContent();
        $timeStamp  = $request->query('timestamp');
        $nonce = $request->query('nonce');
        $msg_sign  = $request->query('msg_signature');
        $encrypt_type = $request->query('encrypt_type');
        $res = app('DecryptXml')->decrypt($notice_data,$msg_sign,$timeStamp,$nonce);
        $log = new Logger('db');
        $log->pushHandler(
            new StreamHandler(
                storage_path('logs/wxPush/'.date('Ymd').'_message'.'.log'),
                Logger::INFO
            )
        );
        $url = url()->full();
        $log->addInfo($notice_data.$url);
        $content_xml = $res['msg'];
        $xml_tree = new DOMDocument();
        $xml_tree->loadXML($content_xml);
        $array_e = $xml_tree->getElementsByTagName('MsgType');
        $type = $array_e->item(0)->nodeValue;
        $to_user_node = $xml_tree->getElementsByTagName('ToUserName');
        $to_user_name = $to_user_node->item(0)->nodeValue;
        $from_user_node = $xml_tree->getElementsByTagName('FromUserName');
        $from_user_name = $from_user_node->item(0)->nodeValue;
        switch($type)
        {
            case 'event':
                $event_node = $xml_tree->getElementsByTagName('Event');
                $event = $event_node->item(0)->nodeValue;
                switch($event)
                {
                    case 'CLICK':
                        $key_node = $xml_tree->getElementsByTagName('EventKey');
                        $key = $key_node->item(0)->nodeValue;
                        $res = app('WxListen')->keyReplyTextContent($from_user_name,$to_user_name,$key,$app_id);
                        if($res['code']==200){
                            return response($res['msg'])->header('Content-Type', 'application/xml');
                        }else{
                            echo '';
                        }
                    case 'subscribe':
                        app('Fans')->addFansRecord($from_user_name,$to_user_name,1);
                    case 'unsubscribe':
                        app('Fans')->addFansRecord($from_user_name,$to_user_name,0);
                }
                break;
            case 'text':
                $content_node = $xml_tree->getElementsByTagName('Content');
                $content = $content_node->item(0)->nodeValue;
                $res = app('WxListen')->wordsReplyTextContent($from_user_name,$to_user_name,$content,$app_id);
                if($res['code']==200){
                    return response($res['msg'])->header('Content-Type', 'application/xml');
                }else{
                    echo '';
                }
                break;
        }
    }
}