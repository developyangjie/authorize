<?php
/**
 * Created by PhpStorm.
 * User  :  沈强
 * Date  :  2018/4/12
 * Time  :  13:06
 * Email :  736025986@qq.com
 */

namespace App\Services;


use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class Material
{
    /**
     * 获取图文素材列表
     * @param $app_id
     * @param $offset 偏移量
     * @param $count  数量
     */
    public function getNewsList($access_token,$offset=0,$count=1){
        $res = app('JSSDK')->getMaterialList($access_token,'news',$offset,$count);
//        $res = '{
//    "item": [
//        {
//            "media_id": "nflTkGUyZKjmU4mFbfkaa0L_kGNvptbubOqyB7_ZSWA",
//            "content": {
//                "news_item": [
//                    {
//                        "title": "测试",
//                        "author": "测试",
//                        "digest": "测试内容",
//                        "content": "<p><img class=\"\" data-copyright=\"0\" data-ratio=\"0.866\" data-s=\"300,640\" data-src=\"https://mmbiz.qpic.cn/mmbiz_jpg/yq4NhIOrQYzZZUtTs427cyqjT57pKDqVJEqw1JgLlHjlIOSljuOoCfuALvJzTo0GtkjBluc2SHKJEQA9RcVoqw/640?wx_fmt=jpeg\" data-type=\"jpeg\" data-w=\"500\" style=\"\"  /></p><p>测试内容<br  /></p>",
//                        "content_source_url": "",
//                        "thumb_media_id": "nflTkGUyZKjmU4mFbfkaa-OcaZoaLOxY-PsjtJRpGRU",
//                        "show_cover_pic": 0,
//                        "url": "http://mp.weixin.qq.com/s?__biz=MzUzMzI4MTYxMg==&mid=100000002&idx=1&sn=b50b97cbade68136663a8e991f9f8e9c&chksm=7aa72f214dd0a6379d894d6c6b8cd99988b79b0b4d6c478f18437e5d937c7d66d1314ade6e23#rd",
//                        "thumb_url": "http://mmbiz.qpic.cn/mmbiz_jpg/yq4NhIOrQYwxE0icm6NXPQp8Am4n9ibHpRy9Bo7NziaHzVwq1eQqhdibY77Ko2fRZ0k6DsgZ4Bc8Y9URMhiarXic4l0A/0?wx_fmt=jpeg",
//                        "need_open_comment": 1,
//                        "only_fans_can_comment": 0
//                    },
//                    {
//                        "title": "再测一下",
//                        "author": "测试",
//                        "digest": "正文一下",
//                        "content": "<p>正文一下</p>",
//                        "content_source_url": "",
//                        "thumb_media_id": "nflTkGUyZKjmU4mFbfkaa1y-XtQ2qGnGLiIQpyg37U4",
//                        "show_cover_pic": 0,
//                        "url": "http://mp.weixin.qq.com/s?__biz=MzUzMzI4MTYxMg==&mid=100000002&idx=2&sn=cc7c5e162707c7af1a672f301ad918a0&chksm=7aa72f214dd0a637114af9b65ca5b9ed4ede1a3248a85caff923228b6f556b06829de3c79c19#rd",
//                        "thumb_url": "http://mmbiz.qpic.cn/mmbiz_jpg/yq4NhIOrQYwxE0icm6NXPQp8Am4n9ibHpR0bRl1njK5toS9icZ2gg1eWT98QY9U8NzkwFt9DD3WUbjMw3RG6S1NJg/0?wx_fmt=jpeg",
//                        "need_open_comment": 1,
//                        "only_fans_can_comment": 0
//                    }
//                ],
//                "create_time": 1523501072,
//                "update_time": 1523514645
//            },
//            "update_time": 1523514645
//        }
//    ],
//    "total_count": 1,
//    "item_count": 1
//}';
        $res_arr = json_decode($res,True);
        if(array_key_exists('errcode',$res_arr)){
            return [
                'code' => 400,
                'msg'  => '查找图文素材失败,错误码：'.$res_arr['errcode'].'错误信息：'.$res_arr['errmsg']
            ];
        }else{
            return [
                'code' => 200,
                'msg'  => $res_arr
            ];
        }
    }

    /**
     * 添加图文消息
     */
    public function addNews($access_token,$data_json){
        $res = app('JSSDK')->createNews($access_token,$data_json);
        $res_arr = json_decode($res,True);
        if(array_key_exists('errcode',$res_arr)){
            return [
                'code' => 400,
                'msg'  => '新增永久图文素材失败,错误码：'.$res_arr['errcode'].'错误信息：'.$res_arr['errmsg']
            ];
        }else{
            return [
                'code' => 200,
                'msg'  => $res_arr
            ];
        }
    }
    /*
     * 图片素材列表
     */
    public function getImageList($access_token,$offset=0,$count=1){
        $res = app('JSSDK')->getMaterialList($access_token,'image',$offset,$count);
        $res_arr = json_decode($res,True);
        if(array_key_exists('errcode',$res_arr)){
            return [
                'code' => 400,
                'msg'  => '查找图片素材失败,错误码：'.$res_arr['errcode'].'错误信息：'.$res_arr['errmsg']
            ];
        }else{
            return [
                'code' => 200,
                'msg'  => $res_arr
            ];
        }
    }
    /*
     *上传图片素材
     */
    public function addImage($access_token,$file_url){
        $res = app('JSSDK')->createMaterial($access_token,$file_url,'image');
        $res_arr = json_decode($res,True);
        if(array_key_exists('errcode',$res_arr)){
            return [
                'code' => 400,
                'msg'  => '新增永久图片素材失败,错误码：'.$res_arr['errcode'].'错误信息：'.$res_arr['errmsg']
            ];
        }else{
            return [
                'code' => 200,
                'msg'  => $res_arr
            ];
        }
    }
    /*
     *上传音频素材
     */
    public function addVoice($access_token,$file_url){
        $res = app('JSSDK')->createMaterial($access_token,$file_url,'voice');
        $res_arr = json_decode($res,True);
        if(array_key_exists('errcode',$res_arr)){
            return [
                'code' => 400,
                'msg'  => '新增永久音频素材失败,错误码：'.$res_arr['errcode'].'错误信息：'.$res_arr['errmsg']
            ];
        }else{
            return [
                'code' => 200,
                'msg'  => $res_arr
            ];
        }
    }
    /*
     *上传视频素材
     */
    public function addVideo($access_token,$file_url){
        $res = app('JSSDK')->createMaterial($access_token,$file_url,'video',$title='',$description='');
        $res_arr = json_decode($res,True);
        if(array_key_exists('errcode',$res_arr)){
            return [
                'code' => 400,
                'msg'  => '新增永久视频素材失败,错误码：'.$res_arr['errcode'].'错误信息：'.$res_arr['errmsg']
            ];
        }else{
            return [
                'code' => 200,
                'msg'  => $res_arr
            ];
        }
    }
    /*
     * 预览图文素材
     */
    public function previewNewMaterial($access_token,$media_id,$wx_name='',$open_id=''){
        if(!empty($wx_name) || !empty($open_id)){
            $arr = [
                'mpnews' => [
                    'media_id' => $media_id
                ],
                'msgtype' => 'mpnews'
            ];
            if(!empty($wx_name)){
                $arr['towxname'] = $wx_name;
            }
            if(!empty($open_id)){
                $arr['touser'] = $open_id;
            }
            $json_str = json_encode($arr);
            $res = app('JSSDK')->previewMaterial($access_token,$json_str);
            $res_arr = json_decode($res,True);
            if($res_arr['errcode']==0){
                return [
                    'code' => 200,
                    'msg'  => $res_arr
                ];
            }else{
                return [
                    'code' => 400,
                    'msg'  => '发送微信预览失败,错误码：'.$res_arr['errcode'].'错误信息：'.$res_arr['errmsg']
                ];
            }
        }else{
            return [
                'code' => 400,
                'msg'  => '用户为空'
            ];
        }
    }
    //客服发送文本消息
    public function sendCustomText($access_token,$content,$open_id){
        $arr = [
            "touser"  => $open_id,
            "msgtype" => 'text',
            'text' => [
                "content"=>$content
            ]
        ];
        $json_str = json_encode($arr);
        app('JSSDK')->customSend($access_token,$json_str);
    }
    //客服发送图文消息
    public function sendCustomNews($access_token,$open_id,$arr){
        $arr = [
            "touser"  => $open_id,
            "msgtype" => 'news',
            "news" => [
                "articles"=>$arr
            ]
        ];
        $json_str = json_encode($arr);
        app('JSSDK')->customSend($access_token,$json_str);
    }
    //保存图片
    public function savePic($data){
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->buffer($data);
        $type_arr = explode('/',$mime_type);
        $type = $type_arr[1];
        if($type=='jpeg'){
            $type='jpg';
        }
        $file_name = uniqid().".{$type}";
        $dir_path = public_path().'/ueditor/php/upload/image/'.date('Ymd').'/';
        if(!file_exists($dir_path)){
            mkdir($dir_path);
        }
        $new_file = '/ueditor/php/upload/image/'.date('Ymd').'/'.$file_name;
        $upload_new_file = $dir_path.$file_name;
        if(file_put_contents($upload_new_file,$data)){
            return $new_file;
        }
        return [
            'code'=>400,
            'msg'=>'保存图片失败'
        ];
    }
    //添加群发历史消息
    public function addHistoryMsg($app_id,$msg_id,$msg_data_id,$media_id,$thumb_url_str,$url_str,$title_str)
    {
        $bind_arr = [$app_id,$msg_id,$msg_data_id,$media_id,$thumb_url_str,$url_str,$title_str,time(),0];
        $query = "insert into `history_send` VALUES ('',?,?,?,?,?,?,?,?,?)";
        DB::insert($query,$bind_arr);
        return [
            'code'=>200,
            'msg'=>'添加历史消息成功'
        ];
    }
    //获取历史消息总数
    public function totalHistoryNums($app_id)
    {
        $query = "select count(*) num from `history_send` WHERE app_id = ? and is_delete = 0";
        $res = DB::selectOne($query,[$app_id]);
        return $res['num'];
    }
    //历史消息列表
    public function historyList($access_token,$app_id,$page,$limit)
    {
        $startNum = ($page-1)*$limit;
        $query = "select * from `history_send` WHERE app_id = ? and is_delete = 0 ORDER BY created_at desc limit ?,?";
        $res = DB::select($query,[$app_id,$startNum,$limit]);
        foreach ($res as $k=>$v){
            $res[$k]['create_date'] = showDate($v['created_at']);
            $title_arr = explode(',',$v['title_str']);
            $count_comment_str = '';
            $msg_data_id = $v['msg_data_id'];
            foreach ($title_arr as $key=>$vo){
                $index = $key;
                $res1 = $this->countComment($access_token,$msg_data_id,$index);
                if($res1['code']==200){
                    $count_comment_str .=$res1['msg'].',';
                }
            }
            $res[$k]['count_comment_str'] = $count_comment_str;
        }
        return $res;
    }
    //获取群发消息的评论总数
    public function countComment($access_token,$msg_data_id,$index)
    {
        $begin = 0;
        $count = 1;
        $type = 0;
        $res = app('JSSDK')->getCommentByHistory($access_token,$msg_data_id,$begin,$count,$type,$index);
        $res_arr = json_decode($res,true);
        if(array_key_exists('errcode',$res_arr) && $res_arr['errcode']!=0){
            return [
                'code' => 400,
                'msg'  => '获取消息评论失败,错误码：'.$res_arr['errcode'].'错误信息：'.$res_arr['errmsg']
            ];
        }else{
            return [
                'code' => 200,
                'msg'  => $res_arr['total']
            ];
        }
    }
    //添加定时发送任务
    public function addSendNewTask($app_id,$send_time,$media_id,$thumb_url_str,$url_str,$title_str){
        $query = 'select * from `send_task` WHERE `app_id`=? and `media_id`=? and `is_delete`=0';
        $res = DB::selectOne($query,[$app_id,$media_id]);
        if($res){
            if($res['status']==0){
                $update_query = 'update `send_task` set `send_time`=? WHERE id=?';
                DB::update($update_query,[$send_time,$res['id']]);
                return [
                    'code' => 200,
                    'msg' => '添加成功'
                ];
            }else{
                $del_query = 'update `send_task` set `is_delete`=1 WHERE id=?';
                DB::update($del_query,[$res['id']]);
            }
        }
        $add_query = 'insert into `send_task` VALUES ("",?,?,?,?,?,?,?,?,?)';
        DB::insert($add_query,[$app_id,$media_id,$thumb_url_str,$url_str,$title_str,$send_time,0,time(),0]);
        return [
            'code' => 200,
            'msg' => '添加成功'
        ];
    }
    //查询是否发布任务
    public function hasSendTask($app_id,$media_id){
        $query = 'select * from `send_task` WHERE `app_id`=? and `media_id`=? and `is_delete`=0 and `status`=0';
        $res = DB::selectOne($query,[$app_id,$media_id]);
        if($res){
            return [
                'code' => 200,
                'data' => $res['send_time']
            ];
        }else{
            return [
                'code' => 400,
                'msg' => '未发布'
            ];
        }
    }
}