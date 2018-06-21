<?php
/**
 * Created by PhpStorm.
 * User  :  沈强
 * Date  :  2018/3/28
 * Time  :  15:55
 * Email :  736025986@qq.com
 */

namespace App\Services;

use App\Http\Lib\WX\DecryptMsg\WXBizMsgCrypt;
use DOMDocument;

class DecryptXml
{
    public function decrypt($xml,$msg_sign,$timeStamp,$nonce){
        $token = env('APP_XUET_TOKEN');
        $encodingAesKey = env('APP_XUET_KEY');
        $appId = env('APP_XUET_ID');
        if(!xml_parser($xml)){
            return [
                'code'=>400,
                'msg'=>'格式不正确'
            ];
        }
        $xml_tree = new DOMDocument();
        $xml_tree->loadXML($xml);
        $array_e = $xml_tree->getElementsByTagName('Encrypt');
        $encrypt = $array_e->item(0)->nodeValue;
//        $array_a = $xml_tree->getElementsByTagName('AppId');
//        $appId = $array_a->item(0)->nodeValue;

        $format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
        $from_xml = sprintf($format, $encrypt);

        $pc = new WXBizMsgCrypt($token, $encodingAesKey, $appId);
        // 第三方收到公众号平台发送的消息
        $msg = '';
        $errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
        if($errCode==0){
            return [
                'code'=>200,
                'msg'=>$msg
            ];
        }else{
            return [
                'code'=>400,
                'msg'=>'解密失败，错误码：'.$errCode
            ];
        }
    }
    //加密xml
    public function encrypt($xml,$timeStamp,$nonce){
        $token = env('APP_XUET_TOKEN');
        $encodingAesKey = env('APP_XUET_KEY');
        $appId = env('APP_XUET_ID');
        $pc = new WXBizMsgCrypt($token, $encodingAesKey, $appId);
        // 第三方收到公众号平台发送的消息
        $msg = '';
        $errCode = $pc->encryptMsg($xml, $timeStamp, $nonce, $msg);
        if($errCode==0){
            return [
                'code'=>200,
                'msg'=>$msg
            ];
        }else{
            return [
                'code'=>400,
                'msg'=>'加密密失败，错误码：'.$errCode
            ];
        }
    }
}