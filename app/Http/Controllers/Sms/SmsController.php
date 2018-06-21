<?php
/**
 * Created by PhpStorm.
 * User  :  刘磊
 * Date  :  2018/3/8
 * Time  :  10:08
 * Email :  369968620@163.com
 */
namespace App\Http\Controllers\Sms;

use App\Http\Controllers\Controller;
use Request;

use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Support\Facades\Storage;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;




class SmsController extends Controller {

    public function smsSend()
    {
        $smsStr = Request::input('sms');
        if(mb_strlen($smsStr) > 0 ){
            $smsList = json_decode($smsStr,true);
            if(is_array($smsList) && count($smsList) > 0){
                if(count($smsList) <= 100 ){
                    $ret = app('Sms')->smsSend($smsList);
                    $data = ['code'=>'200',"data"=>$ret];
                }else{
                    $data = ['code'=>"200",'msg'=>'单次发送短信数量不能超过100条'];
                }
            }else{
                $data = ['code'=>"400",'msg'=>'短信内容为空!'];
            }
        }else{
            return response('File not found', 404)->header('Content-Type', 'text/plain');
        }
        return $data;
    }

    public function smsListenLy()
    {
        $data['receiver'] = Request::input('receiver');
        $data['pswd'] = Request::input('pswd');
        $data['msgid'] = Request::input('msgid');
        $data['reportTime'] = Request::input('reportTime');
        $data['mobile'] = Request::input('mobile');
        $data['status'] = Request::input('status');
        app('Sms')->smsListenLy($data);
        $log = new Logger('Ly');
        $log->pushHandler(
            new StreamHandler(
                storage_path('logs/sms/Ly/'.date('Ymd').'.log'),
                Logger::INFO
            )
        );
        $log->addInfo($this->array2string($data));
    }

    private function array2string($array){
        $string = [];
        if($array && is_array($array)){
            foreach ($array as $key=> $value){
                $string[] = $key.':'.$value;
            }
        }
        return implode(',',$string);
    }
}