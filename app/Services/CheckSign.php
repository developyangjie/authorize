<?php
/**
 * Created by PhpStorm.
 * User  :  刘磊
 * Date  :  2018/1/28
 * Time  :  16:58
 * Email :  369968620@163.com
 */
namespace App\Services;

use Illuminate\Support\Facades\DB;

class CheckSign
{
    public function checkSign($data)
    {
        if(isset($data['sign']) && mb_strlen($data['sign']) > 0){
            if(isset($data['authorize_id']) && !empty($data['authorize_id'])){
                ksort($data);
                $buff = "";
                foreach ($data as $k => $v)
                {
                    if($k != "sign" && $v != "" && !is_array($v)){
                        $buff .= $k . "=" . $v . "&";
                    }
                }
                $buff = trim($buff, "&");
                $sql = "select private_key,public_key from authorize_secret where authorize_id = ?";
                $row = DB::selectOne($sql,[$data['authorize_id']]);
                if(isset($row) && !empty($data['authorize_id'])){
//                    $arr = [
//                        ['custom_id'=>'20180308123','accept'=>'17612163856','content'=>'【365学堂】这是另一条测试短信'],
//                        ['custom_id'=>'20180308124','accept'=>'17612163856','content'=>'【365学堂】这是另一条测试短信'],
//                        ['custom_id'=>'20180308125','accept'=>'12612163856','content'=>'【365学堂】这是另一条测试短信'],
//                        ['custom_id'=>'20180308126','accept'=>'17612163856','content'=>'【365学堂】这是另一条测试短信']
//                    ];
//                    echo json_encode($arr);
//                    exit;
//                openssl_sign($buff,$sign,$row['private_key']);
//                var_dump(base64_encode($sign));
//                exit;
                    $verify = openssl_verify($buff,base64_decode($data['sign']),$row['public_key']);
                    if($verify){
                        return ['state'=>true];
                    }else{
                        return ['state'=>false,'msg'=>'签名错误!'];
                    }
                }else{
                    return ['state'=>false,'msg'=>'未被授权的authorize_id!'];
                }
            }else{
                return ['state'=>false,'msg'=>'错误的authorize_id!'];
            }
        }else{
            return ['state'=>false,'msg'=>'签名错误!'];
        }
    }

    /**
     * 创建一个授权凭据
     */
    public function create(){
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privKey);
        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey["key"];
        $sql = "insert into authorize_secret (private_key,public_key,created_at,updated_at) value (?,?,?,?)";
        DB::insert($sql,[$privKey,$pubKey,time(),time()]);
    }


}