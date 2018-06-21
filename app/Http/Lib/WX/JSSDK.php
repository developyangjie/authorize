<?php
namespace App\Http\Lib\WX;
class JSSDK
{
    private $appId;
    private $appSecret;
    protected $values = array();

    public function init($appId,$appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        return $this;
    }

    public function getSignPackage()
    {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId" => $this->appId,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public function getJsApiTicket($accessToken)
    {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
        $res = json_decode($this->httpGet($url),true);
        if(json_last_error() == null){
            $ret = ['state'=>true,'data'=>$res];
        }else{
            $ret = ['state'=>false,'msg'=>'系统繁忙!'];
        }
        return $ret;
    }

    public function getAccessToken()
    {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
        $res = json_decode($this->httpGet($url),true);
        if(json_last_error() == null){
            $ret = ['state'=>true,'data'=>$res];
        }else{
            $ret = ['state'=>false,'msg'=>'系统繁忙!'];
        }
        return $ret;
    }

    public function getComponentAccessToken($component_appid,$component_appsecret,$component_verify_ticket)
    {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_component_token";
        $data = [
            'component_appid'=>$component_appid,
            'component_appsecret'=>$component_appsecret,
            'component_verify_ticket'=>$component_verify_ticket
        ];
        $data = json_encode( $data );
        $res = curl_api_post($url,$data);
        return $res;
    }

    /*
     * 获取预授权码
     */
    public function getPreAuthCode($component_appid,$component_access_token){
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=".$component_access_token;
        $data = [
            'component_appid'=>$component_appid,
        ];
        $data = json_encode( $data );
        $res = curl_api_post($url,$data);
        return $res;
    }

    /*
     * 获取授权token接口
     */
    public function getAuthToken($component_appid,$component_access_token,$authorization_code){
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=".$component_access_token;
        $data = [
            'component_appid'   => $component_appid,
            "authorization_code"=> $authorization_code
        ];
        $data = json_encode( $data );
        $res = curl_api_post($url,$data);
        return $res;
    }

    /*
     * 刷新授权token接口
     */
    public function refreshAuthToken($component_appid,$component_access_token,$auth_appid,$authorizer_refresh_token){
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token=".$component_access_token;
        $data = [
            'component_appid'   => $component_appid,
            "authorizer_appid"  => $auth_appid,
            "authorizer_refresh_token"=> $authorizer_refresh_token
        ];
        $data = json_encode( $data );
        $res = curl_api_post($url,$data);
        return $res;
    }
    /*
     * 获取授权方的帐号基本信息
     */
    public function getAuthorizerInfo($component_access_token,$component_appid,$auth_appid){
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token=".$component_access_token;
        $data = [
            'component_appid'   => $component_appid,
            "authorizer_appid"  => $auth_appid
        ];
        $data = json_encode( $data );
        $res = curl_api_post($url,$data);
        return $res;
    }
    /*
     * 查询自定义菜单接口
     */
    public function getMenuList($access_token){
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$access_token;
        $res = $this->httpGet($url);
        return $res;
    }
    /*
     * 创建自定义菜单接口
     */
    public function createMenuList($access_token,$data_json){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
        $res = curl_api_post($url,$data_json);
        return $res;
    }
    /*
     * 查询素材接口
     */
    public function getMaterialList($access_token,$type,$offset,$count){
        $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$access_token;
        $data = [
            'type'   => $type,
            "offset"  => $offset,
            "count"=> $count
        ];
        $data = json_encode( $data );
        $res = curl_api_post($url,$data);
        return $res;
    }
    /*
     * 新增永久图文素材
     */
    public function createNews($access_token,$data_json){
        $url = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=".$access_token;
        $res = curl_api_post($url,$data_json);
        return $res;
    }
    /*
     *新增其他类型永久素材
     */
    public function createMaterial($access_token,$file_url,$type,$title='',$description=''){
        $file_url =public_path().$file_url;
        $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".$access_token.'&type='.$type;
        $data['media'] = curl_file_create($file_url);
        if($type=='video'){
            $data['title'] =$title;
            $data['description'] =$description;
        }
        $res = curl_api_post($url,$data);
        return $res;
    }
    /*
     * 上传图文消息内的图片获取URL
     */
    public function uploadimg($access_token,$img_url){
        $img_url =public_path().$img_url;
        $url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?type=image&access_token=".$access_token;
        $data['media'] = curl_file_create($img_url);
        $res = curl_api_post($url,$data);
        if(file_exists($img_url)){
            @unlink($img_url);
        }
        return $res;
    }
    /*
     * 获取永久类型素材
     */
//    private function getFileType($fileName)
//    {
//        $fileInfo = pathinfo($fileName);
//        switch($fileInfo['extension']){
//            case 'png':
//                return 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?type=image';
//                break;
//            case 'jpg':
//                return 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?type=image';
//                break;
//        }
//    }

    /*
     * 获取永久素材
     */
    public function getMaterialByMediaId($access_token,$media_id){
        $url = "https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=".$access_token;
        $data = [
            'media_id'   => $media_id
        ];
        $data = json_encode( $data );
        $res = curl_api_post($url,$data);
        return $res;
    }
    /**
     * @param $access_token
     * @param $media_id
     * @return mixed|string
     */
    public function delMaterial($access_token,$media_id){
        $url = "https://api.weixin.qq.com/cgi-bin/material/del_material?access_token=".$access_token;
        $data = [
            'media_id'   => $media_id,
        ];
        $data = json_encode($data);
        $res = curl_api_post($url,$data);
        return $res;
    }
    /*
     * 修改图文
     */
    public function updateNews($access_token,$json_str){
        $url = "https://api.weixin.qq.com/cgi-bin/material/update_news?access_token=".$access_token;
        $res = curl_api_post($url,$json_str);
        return $res;
    }
    /*
     * 发送微信号预览图文
     */
    public function previewMaterial($access_token,$json_str){
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=".$access_token;
        $res = curl_api_post($url,$json_str);
        return $res;
    }
    /**
     * 群发图文消息
     */
    public function sendallNews($access_token,$media_id,$tag_id=0){
        if($tag_id){
            $is_to_all = false;
        }else{
            $is_to_all = true;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=".$access_token;
        $arr = [
            'filter'=>[
                'is_to_all' => $is_to_all,
                'tag_id' => $tag_id,
            ],
            'mpnews'=>[
                'media_id'=>$media_id
            ],
            'msgtype'=>'mpnews',
            'send_ignore_reprint'=>1
        ];
        $json_str = json_encode($arr);
        $res = curl_api_post($url,$json_str);
        return $res;
    }
    /*
     * 客服消息接口
     */
    public function customSend($access_token,$json_str){
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
        $res = curl_api_post($url,$json_str);
        return $res;
    }
    /*
     * 获取用户列表
     */
    public function getUsers($access_token,$next_openid=''){
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token."&next_openid=".$next_openid;
        $res = $this->httpGet($url);
        return $res;
    }
    //获取群发消息的评论
    public function getCommentByHistory($access_token,$msg_data_id,$begin,$count,$type=0,$index=1){
        $url = "https://api.weixin.qq.com/cgi-bin/comment/list?access_token=".$access_token;
        $arr = [
            'msg_data_id' => $msg_data_id,
            'index' => $index,
            'begin' => $begin,
            'count' => $count,
            'type' => $type,
        ];
        $json_str = json_encode($arr);
        $res = curl_api_post($url,$json_str);
        return $res;
    }
    /*
     * 
     */
    private function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
    //批量下载图片
    public function multiDownImage($url_arr=[],$max_process_num=30){
        $result = [];
        $num = count($url_arr);
        foreach ($url_arr as $v){
            $data[]=[
                'url'=>$v,
                'data'=>''
            ];
        }
        $res = my_multi_curl($data,'get');
        foreach ($res as $file_data){
            $result[] = app('Material')->savePic($file_data);
        }
        return $result;
    }
    //批量上传图片
    public function multiUploadImage($access_token,$img_url_arr=[],$max_process_num=30){
        $res = [];
        $url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?type=image&access_token=".$access_token;
        foreach ($img_url_arr as $key=>$v){
            $img_url =public_path().$v;
            $img_url_arr[$key]=$img_url;
            $data['media'] = curl_file_create($img_url);
            $curl_data[]=[
                'url'=>$url,
                'data'=>$data
            ];
        }
        $res = array_merge($res,my_multi_curl($curl_data));
//        foreach ($img_url_arr as $img_url){
//            if(file_exists($img_url)){
//                @unlink($img_url);
//            }
//        }
        return $res;
    }
    //获取用户列表

    public function getCodeUrl($state,$redirect_uri,$scope="0")
    {
        if($scope == "0"){
            $scope = "snsapi_base";
        }else{
            $scope = "snsapi_userinfo";
        }
        return $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->appId&redirect_uri=" . urlencode($redirect_uri) . "&response_type=code&scope=".$scope."&state=" . $state . "#wechat_redirect";
    }

    public function getopenid()
    {
        $code = Request::get('code');
        $state = Request::get('state');
        if (isset($code) && !empty($code) && isset($state)) {
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appId&secret=$this->appSecret&code=$code&grant_type=authorization_code ";
            $ret = $this->httpGet($url);
            return $ret;
        } else {
            $this->getCode();
            exit;
        }
    }


    public function getuserinfo()
    {
        $code = Request::get('code');
        $state = Request::get('state');
        if (isset($code) && !empty($code) && isset($state)) {
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appId&secret=$this->appSecret&code=$code&grant_type=authorization_code ";
            $ret = $this->httpGet($url);
            $access =  json_decode($ret, true);

            if ($access && json_last_error() == null) {
                if(is_array($access) && isset($access['openid']) && isset($access['access_token'])){
                    $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access['access_token'].'&openid='.$access['openid'].'&lang=zh_CN';
                    $ret = $this->httpGet($url);
                    $data = json_decode($ret,true);
                    return $data;
                }else{
                    $this->getCode();
                    exit;
                }
            }else{
                $this->getCode();
                exit;
            }
        } else {
            $this->getCode();
            exit;
        }
    }

    private function get_php_file($filename)
    {
        return trim(substr(file_get_contents(storage_path('app/public/wx'). DIRECTORY_SEPARATOR . $filename), 15));
    }

    private function set_php_file($filename, $content)
    {
        $fp = fopen(storage_path('app/public/wx') . DIRECTORY_SEPARATOR . $filename, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }

    /**
     *
     * 统一下单，WxPayUnifiedOrder中out_trade_no、body、total_fee、trade_type必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayUnifiedOrder $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public function unifiedOrder($order_info,$timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $timeStamp = time();
        $this->values['appid'] = env('WX_APPID');
        $this->values['mch_id'] = env('WX_MCH_ID');
        $this->values['nonce_str'] = $this->createNonceStr(32);
        $this->values['openid'] = $order_info['openid'];
        $this->values['body'] = $order_info['order_msg'];
        $this->values['out_trade_no'] = $order_info['out_trade_no'];
        $this->values['total_fee'] =  $order_info['total_fee'];
        $this->values['spbill_create_ip'] = $order_info['spbill_create_ip'];
        $this->values['notify_url'] = env('WX_NOTIFY_URL');
        $this->values['trade_type'] = 'JSAPI';
        $this->values['timeStamp'] = $timeStamp;
        $this->SetSign();
        $xml = $this->ToXml();
        $response_xml = self::postXmlCurl($xml, $url, false, $timeOut);
        $response = $this->FromXml($response_xml);
        $response['timeStamp'] = $timeStamp;
        return $response;
    }

    public function paySign($arr)
    {
        //签名步骤一：按字典序排序参数
        ksort($arr);
        $buff = "";
        foreach ($arr as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        //签名步骤二：在string后加入KEY
        $string = $buff . "&key=".env('WX_PAY_KEY');
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    public function SetSign()
    {
        $sign = $this->MakeSign();
        $this->values['sign'] = $sign;
        return $sign;
    }


    /**
     * 格式化参数格式化成url参数
     */
    public function ToUrlParams()
    {
        $buff = "";
        foreach ($this->values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    public function MakeSign()
    {
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->ToUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".env('WX_PAY_KEY');
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 输出xml字符
     * @throws WxPayException
     **/
    public function ToXml()
    {
        if(!is_array($this->values)
            || count($this->values) <= 0)
        {
            throw new WxPayException("数组数据异常！");
        }

        $xml = "<xml>";
        foreach ($this->values as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function FromXml($xml)
    {
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->values;
    }

    /**
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    private static function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);


        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//
//        if($useCert == true){
//            //设置证书
//            //使用证书：cert 与 key 分别属于两个.pem文件
//            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
//            curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
//            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
//            curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
//        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return $error;
        }
    }
}

