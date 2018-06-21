<?php
/**
 * Created by PhpStorm.
 * User  :  liulei
 * Date  :  2017/8/25
 * Time  :  16:12
 * Email :  369968620@qq.com
 */

/**
 * 过滤空格换行
 * @param $str
 * @return mixed
 */
function myTrim($str)
{
    $search = array(" ","　","\n","\r","\t");
    $replace = array("","","","","");
    return str_replace($search, $replace, $str);
}

function des_decrypt($data,$key){
    $text = base64_decode(urldecode($data));
    return pkcs5Unpad(mcrypt_decrypt(MCRYPT_3DES, $key, $text, MCRYPT_MODE_ECB));
}

function pkcs5Unpad($text)
{
    $pad = ord($text{strlen($text) - 1});
    if ($pad > strlen($text)) {
        return false;
    }
    if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
        return false;
    }
    return substr($text, 0, -1 * $pad);
}


function curl_api_post($url,$data=array(),$header=array()){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10); //timeout on connect
    curl_setopt ( $ch, CURLOPT_TIMEOUT, 10); //timeout on response
    curl_setopt ( $ch, CURLOPT_POSTFIELDS,$data);
    $return = curl_exec ( $ch );
    if($return === false){
        $err =  curl_error($ch);
        curl_close ( $ch );
        return $err;
    }else{
        curl_close ( $ch );
        return $return;
    }
}

/**
 * 返回一个随机字符串
 * @param $length
 * @return null|string
 */
function get_randChar($length){
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol)-1;

    for($i=0;$i<$length;$i++){
        $str.=$strPol[rand(0,$max)];
    }
    return $str;
}

/**
 * 判断是否是手机号
 * @param $phone
 * @return bool
 */
function is_mobile($phone){
    if(preg_match("/^1[3456789]{1}\d{9}$/",$phone)){
        return true;
    }else{
        return false;
    }
}
/*
 * 判断是xml
 */
function xml_parser($str){
    $xml_parser = xml_parser_create();
    if(!xml_parse($xml_parser,$str,true)){
        xml_parser_free($xml_parser);
        return false;
    }
    return true;
}
/*
 * 日期显示
 */
function showDate($time){
    $year = date('Y',time());
    $month = date('m',time());
    $day = date('d',time());
    $yesterday = date('d',time()-24*3600);
    $show_year = date('Y',$time);
    $show_month = date('m',$time);
    $show_day = date('d',$time);

    if($year == $show_year && $month==$show_month && $day==$show_day){
        return date('H:i',$time);
    }elseif($year == $show_year && $month==$show_month && $yesterday==$show_day){
        return '昨天 '.date('H:i',$time);
    }else{
        return date('y-m-d H:i',$time);
    }
}
//截取字符添加...
function my_substr($str,$length){
    $str_length = mb_strlen($str);
    if($str_length<=$length){
        return $str;
    }else{
        return mb_substr($str,0,$length).'...';
    }
}


function my_multi_curl($arr=[],$type='post'){
    // curl会话
    $ch = array();
    // 执行结果
    $result = array();
    // 创建curl handle
    $mh = curl_multi_init();
    // 循环设定数量
    foreach($arr as $k=>$v){
        $ch[$k] = curl_init();
        curl_setopt ( $ch[$k], CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt ( $ch[$k], CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch[$k], CURLOPT_URL, $v['url']);
        if($type=='post'){
            curl_setopt ( $ch[$k], CURLOPT_POST, true );
        }else{
            curl_setopt ( $ch[$k], CURLOPT_HTTPGET, true );
        }
        curl_setopt ( $ch[$k], CURLOPT_HEADER, false );
        curl_setopt($ch[$k], CURLINFO_HEADER_OUT, true);
        curl_setopt($ch[$k], CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ( $ch[$k], CURLOPT_CONNECTTIMEOUT, 10); //timeout on connect
        curl_setopt ( $ch[$k], CURLOPT_TIMEOUT, 10);
        if(!empty($v['data'])){
            curl_setopt ( $ch[$k], CURLOPT_POSTFIELDS,$v['data']);
        }
        // 加入处理
        curl_multi_add_handle($mh, $ch[$k]);
    }
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);

    foreach($arr as $k=>$v){
        $result[$k] = curl_multi_getcontent($ch[$k]);
    }
    foreach($arr as $k=>$val){
        curl_multi_remove_handle($mh,$ch[$k]);
    }
    curl_multi_close($mh);
    return $result;
}
