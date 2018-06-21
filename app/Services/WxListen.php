<?php
/**
 * Created by PhpStorm.
 * User  :  刘磊
 * Date  :  2018/3/5
 * Time  :  18:11
 * Email :  369968620@163.com
 */
namespace App\Services;

use App\Http\Lib\WX\DecryptMsg\Prpcrypt;
use Illuminate\Support\Facades\DB;
use DOMDocument;

class WxListen {

    public function addTicket($msg){
        $xml_tree = new DOMDocument();
        $xml_tree->loadXML($msg);

        $AppId_node = $xml_tree->getElementsByTagName('AppId');
        $AppId = $AppId_node->item(0)->nodeValue;

        $CreateTime_node = $xml_tree->getElementsByTagName('CreateTime');
        $CreateTime = $CreateTime_node->item(0)->nodeValue;

        $InfoType_node = $xml_tree->getElementsByTagName('InfoType');
        $InfoType = $InfoType_node->item(0)->nodeValue;

        $ComponentVerifyTicket_node = $xml_tree->getElementsByTagName('ComponentVerifyTicket');
        $ComponentVerifyTicket = $ComponentVerifyTicket_node->item(0)->nodeValue;
        $query = "update `component_info` set component_verify_ticket=?,ticket_update_time=?,ticket_info_type=? WHERE app_id=?";
        DB::update($query,[$ComponentVerifyTicket,$CreateTime,$InfoType,$AppId]);
    }
    //菜单回复文本消息
    public function keyReplyTextContent($toUserName,$fromUserName,$key,$app_id){
        $res = app('Wx')->getTextByKey($key,$app_id);
        if($res['code']==200){
            $reply_text = $res['msg'];
            $res = $this->replyTextContent($toUserName,$fromUserName,$reply_text);
        }
        return $res;
    }
    //关键词回复文字消息
    public function wordsReplyTextContent($toUserName,$fromUserName,$keyWord,$app_id){
        $query = "select * from text_receive where is_delete =0 and app_id =? and ((key_word like ? and match_type=1) or (key_word=? and match_type=0))";
        $res = DB::selectOne($query,[$app_id,"%$keyWord%",$keyWord]);
        if($res){
            $replay_arr = explode(',',$res['reply_id_str']);
            foreach ($replay_arr as $vo){
                $replay_arr1 = explode('|',$vo);
                $reply_type = $replay_arr1[0];
                $reply_id = $replay_arr1[1];
                switch($reply_type){
                    case 'text':
                        $query = "select `reply_text` from `text_reply` where id=?";
                        $res = DB::selectOne($query,[$reply_id]);
                        if($res){
                            $reply_text = $res['reply_text'];
                            $res = $this->replyTextContent($toUserName,$fromUserName,$reply_text);
                            return $res;
                        }else{
                            return [
                                'code'=>400,
                                'msg'=>'未找到回复内容'
                            ];
                        }
                    case 'news':
                        $id_arr = explode(';',$reply_id);
                        $id_str = '';
                        foreach ($id_arr as $vo1){
                            $id_str .= $vo1.',';
                        }
                        $id_str = rtrim($id_str,',');
                        $query = "select * from `news_reply` where id in ($id_str)";
                        $result = DB::select($query);
                        $res = $this->replyNewsContent($toUserName,$fromUserName,$result);
                        return $res;
                }
            }
        }else{
            return [
                'code'=>400,
                'msg'=>'未找到关键词'
            ];
        }
    }
    //回复文字消息
    public function replyTextContent($toUserName,$fromUserName,$reply_text){
        $timeStamp = time();
        $prp = new Prpcrypt('');
        $nonce = $prp->getRandomStr();
        $xml_str = <<<xml
            <xml>
                 <ToUserName><![CDATA[%s]]></ToUserName>
                 <FromUserName><![CDATA[%s]]></FromUserName>
                 <CreateTime>%d</CreateTime>
                 <MsgType><![CDATA[text]]></MsgType>
                 <Content><![CDATA[%s]]></Content>
            </xml>
xml;
        $xml_str = sprintf($xml_str,$toUserName,$fromUserName,$timeStamp,$reply_text);
        $res_xml = app('DecryptXml')->encrypt($xml_str,$timeStamp,$nonce);
        return $res_xml;
    }
    //回复图文消息消息
    public function replyNewsContent($toUserName,$fromUserName,$news_arr){
        $timeStamp = time();
        $prp = new Prpcrypt('');
        $nonce = $prp->getRandomStr();
        $item_str = '';
        foreach ($news_arr as $v){
            $item_str .= <<<item
<item>
                 <Title><![CDATA[%s]]></Title>
                 <Description><![CDATA[%s]]></Description>
                 <PicUrl><![CDATA[%s]]></PicUrl>
                 <Url><![CDATA[%s]]></Url>
             </item>
item;
            $item_str = sprintf($item_str,$v['title'],$v['description'],$v['pic_url'],$v['url']);
        }
        $xml_str = <<<xml
            <xml>
                 <ToUserName><![CDATA[%s]]></ToUserName>
                 <FromUserName><![CDATA[%s]]></FromUserName>
                 <CreateTime>%d</CreateTime>
                 <MsgType><![CDATA[news]]></MsgType>
                 <ArticleCount>%d</ArticleCount>
                 <Articles>
                           $item_str
                 </Articles>
            </xml>
xml;
        $xml_str = sprintf($xml_str,$toUserName,$fromUserName,$timeStamp,count($news_arr));
//        dd($xml_str);
        $res_xml = app('DecryptXml')->encrypt($xml_str,$timeStamp,$nonce);
        return $res_xml;
    }
}