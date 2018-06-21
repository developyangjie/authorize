<?php
/**
 * Created by PhpStorm.
 * User  :  沈强
 * Date  :  2018/3/26
 * Time  :  17:59
 * Email :  736025986@qq.com
 */

namespace App\Http\Controllers\Index;


use App\Http\Requests\AddNewsReply;
use App\Http\Requests\AddReply;
use Illuminate\Http\Request;

class ReplyController
{
    public $app_id;
    public $app_secret;

    function __construct()
    {
        $this->app_id = env('APP_XUET_ID');
        $this->app_secret = env('APP_XUET_SECRET');
    }

    public function index()
    {
        return view('index.replyIndex');
    }
    //回复dataTable列表
    public function replyListPost()
    {
        $return = [];
        $app_id = session('admin_user_info.app_id');
        $res = app('Wx')->getReplyList($app_id);
        $recordsFiltered = count($res);
        $recordsTotal = count($res);
        $return['recordsFiltered'] = $recordsFiltered;
        $return['recordsTotal'] = $recordsTotal;
        $return['data'] = $res;
        return $return;
    }
    //添加回复
    public function add(){
        return view('index.replyAdd');
    }
    //编辑回复
    public function edit(Request $request){
        $id = $request->query('id');
        $res = app('Wx')->getReplyInfo($id);
//        dd($res);
        return view('index.replyAdd',[
            'res'=>$res
        ]);
    }
    public function addPost(AddReply $request){
        $match_type= $request->input('match_type');
        $key_word= $request->input('key_word');
        $app_id = session('admin_user_info.app_id');
        $reply_id_str = $request->input('reply_id_str');
        $id = $request->input('id',0);
        if($id>0){
            $res = app('Wx')->updateReply($key_word,$match_type,$reply_id_str,$id);
        }else{
            $res = app('Wx')->addReply($key_word,$match_type,$reply_id_str,$app_id);
        }

        return $res;
    }
    //删除回复规则
    public function delReply(Request $request){
        $id = $request->input('id');
        $res = app('Wx')->delReply($id);
        return $res;
    }
    //新增文字素材
    public function addTextMaterialPost(Request $request){
        $app_id = session('admin_user_info.app_id');
        $reply_text = $request->input('reply_text');
        if($reply_text==''){
            return [
                'code'=>400,
                'msg'=>'请填写回复内容'
            ];
        }
        $res = app('Wx')->addTextMaterial($app_id,$reply_text);
        return $res;
    }
    //新增图文回复
    public function addNewsMaterialPost(AddNewsReply $request){
        $app_id = session('admin_user_info.app_id');
        $title = $request->input('title');
        $description = $request->input('description');
        $pic_url = $request->input('pic_url');
        $url = $request->input('url');
        $res = app('Wx')->addNewsMaterial($app_id,$pic_url,$title,$description,$url);
        return $res;
    }
    //编辑图文回复
    public function editNewsMaterialPost(AddNewsReply $request){
        $id = $request->input('reply_id');
        $title = $request->input('title');
        $description = $request->input('description');
        $pic_url = $request->input('pic_url');
        $url = $request->input('url');
        $res = app('Wx')->editNewsMaterial($id,$pic_url,$title,$description,$url);
        return $res;
    }
    //编辑文字素材
    public function editTextMaterialPost(Request $request){
        $reply_text = $request->input('reply_text');
        $id = $request->input('reply_id');
        if($reply_text==''){
            return [
                'code'=>400,
                'msg'=>'请填写回复内容'
            ];
        }
        $res = app('Wx')->editTextMaterial($id,$reply_text);
        return $res;
    }
    //获取所有文字回复
    public function getAllReply(Request $request){
        $start = $request->input('start');
        $length = $request->input('length');
        $reply_type = $request->input('reply_type');
        $return = [];
        $app_id = session('admin_user_info.app_id');
        $total_res = app('Wx')->getAllReply($reply_type,$app_id);
        $res = app('Wx')->getAllReply($reply_type,$app_id,$start,$length);
        $recordsFiltered = count($total_res);
        $recordsTotal = count($total_res);
        $return['recordsFiltered'] = $recordsFiltered;
        $return['recordsTotal'] = $recordsTotal;
        $return['data'] = $res;
        return $return;
    }
    //获取回复详情
    public function getReplyDetail(Request $request){
        $id = $request->input('reply_id');
        $reply_type = $request->input('reply_type');
        $res = app('Wx')->getReplyDetailById($reply_type,$id);
        return $res;
    }
}