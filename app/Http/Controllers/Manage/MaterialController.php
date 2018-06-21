<?php

namespace App\Http\Controllers\Manage;

use Couchbase\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialController extends Controller
{
    //图文消息
    public function newsIndex(Request $request,$page=1){
        $token_res = $this->getAuthToken();
//        $token_res = [
//            'code' => 200,
//            'AuthorizerAccessToken' => '12dfs'
//        ];
        if($token_res['code']==200){
            $access_token = $token_res['AuthorizerAccessToken'];
            $count = 10;
            $offset = ($page-1)*$count;
            $res = app('Material')->getNewsList($access_token,$offset,$count);
//            dd($res['msg']);
            if($res['code']==200){
                $total_num = $res['msg']['total_count'];
                $total_page = ceil($total_num/$count);
                return view('material.materialIndex',['data'=>$res['msg'],'total_page'=>$total_page,'page'=>$page]);
            }
            $token_res = $res;
        }
        echo "<script>alert('".$token_res['msg']."')</script>";
    }
    //图文消息post
    public function newsListPost(Request $request){
        $token_res = $this->getAuthToken();
        if($token_res['code']==200){
            $access_token = $token_res['AuthorizerAccessToken'];
            $page = $request->input('page');
            $count = 10;
            $offset = ($page-1)*$count;
            $res = app('Material')->getNewsList($access_token,$offset,$count);
            if($res['code']==200){
                $total_num = $res['msg']['total_count'];
                $total_page = ceil($total_num/$count);
                $res['msg']['total_page'] = $total_page;
                return $res;
            }
        }
        return $token_res;
    }
    //保存剪裁图片
    public function saveCropImg(Request $request){
        $token_res = $this->getAuthToken();
        if($token_res['code']==200){
            $access_token = $token_res['AuthorizerAccessToken'];
            $img_content = $request->input('img_data_url');
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content, $result)){
                $type = $result[2];
                $file_name = uniqid().".{$type}";
                $new_file = '/ueditor/php/upload/image/'.date('Ymd').'/'.$file_name;
                $upload_new_file = public_path().$new_file;
                if (file_put_contents($upload_new_file, base64_decode(str_replace($result[1], '', $img_content)))){
                    $res = app('Material')->addImage($access_token,$new_file);
                    if(file_exists($upload_new_file)){
                        unlink($upload_new_file);
                    }
                    return $res;
//                    echo '新文件保存成功：', $new_file;
                }
            }
        }
    }
    //新建图文消息
    public function newsAdd(){
        return view('material.newsAdd');
    }
    //新建图文消息post
    public function newsAddPost(Request $request){
        set_time_limit(0);
        $token_res = $this->getAuthToken();
        $news_list_str = urldecode($request->input('news_str'));
        if($token_res['code']==200){
            $access_token = $token_res['AuthorizerAccessToken'];
            $news_list_arr = json_decode($news_list_str,true);
            foreach ($news_list_arr['articles'] as $k=>$v){
                $content = $v['content'];
                //需要下载的图片
                $multi_down_arr = [];
                //需要上传的图片
                $multi_upload_arr = [];
                //需要替换的图片
                $replace_arr = [];
                //替换图片的数组
                $img_replace_arr = [];
                //测试打印数组
//                $test_print_arr = [];
                $preg = '/background-image:\s*url\(\S+\)/';
                preg_match_all($preg,$content,$match);
                //循环匹配到的背景图片
                foreach ($match[0] as $pic){
                    $arr = explode('&quot;',$pic);
                    $url = $arr[1];
                    if(strpos($url,'http')!==false){
                        $multi_down_arr[] = $url;
                    }else{
                        $multi_upload_arr[] = $url;
                    }

                }
                //循环匹配到的img图片
                $dom = \phpQuery::newDocumentHTML($content);
                $img_ele = pq("img");
                foreach ($img_ele as $vo){
                    $vo_src = pq($vo)->attr('src');
                    //如果是网络图片
                    if(strpos($vo_src,'http')!==false){
                        $multi_down_arr[] = $vo_src;
                    }else{
                        $multi_upload_arr[] = $vo_src;
                    }
                }
                //需要替换的原图片地址
                $replace_arr = array_merge($multi_upload_arr,$multi_down_arr);
                //下载远程图片到本地
                if($multi_down_arr){
                    $down_path_arr = app('JSSDK')->multiDownImage($multi_down_arr,100);
                    foreach ($down_path_arr as $key=>$host_path){
                        $multi_upload_arr[] = $host_path;
                    }
                }
                //上传到微信
                if($multi_upload_arr){
                    $uploaded_arr = app('JSSDK')->multiUploadImage($access_token,$multi_upload_arr);
                    foreach ($uploaded_arr as $key=>$wx_path){
                        $arr = json_decode($wx_path,true);
                        if(isset($arr) && array_key_exists('url',$arr)){
                            $new_url = json_decode($wx_path,true)['url'];
                        }else{
                            $new_url = '';
                        }
                        $img_replace_arr[] = $new_url;
                    }
                }
//                var_dump($replace_arr);
//                dd($img_replace_arr);
                $content = str_replace($replace_arr,$img_replace_arr,$content);
                $news_list_arr['articles'][$k]['content'] = $content;
            }
            $res = app('Material')->addNews($access_token,json_encode($news_list_arr,JSON_UNESCAPED_UNICODE));
            return $res;
        }
        return $token_res;
    }
    //图片素材
    public function imageIndex(){
//        return view('material.imageIndex',['data'=>['total_count'=>1,'item'=>[]]]);
        $res = $this->imageList(0,10);
        if($res['code']==200){
            return view('material.imageIndex',['data'=>$res['msg']]);
        }
        echo "<script>alert('".$res['msg']."')</script>";
    }
    //获取图片素材数据
    public function imageListPost(Request $request){
        $offset = $request->input('offset');
        $count = $request->input('count');
        $res = $this->imageList($offset,$count);
        return $res;
    }
    private function imageList($offset,$count){
        $token_res = $this->getAuthToken();
        if($token_res['code']==200){
            $access_token = $token_res['AuthorizerAccessToken'];
            $res = app('Material')->getImageList($access_token,$offset,$count);
            return $res;
        }
        $res = $token_res;
        return $res;
    }
    //上传图片
    public function imageAddPost(Request $request){
        $token_res = $this->getAuthToken();
        if($token_res['code']==200){
            if($request->hasFile('image')) {
                $file_arr = $request->file('image');
                foreach ($file_arr as $file){
                    $clientName = $file->getClientOriginalName();
                    $path = '/ueditor/php/upload/image/';
                    $savePath = public_path().$path;
                    /*以时间来命名上传的文件*/
                    $str = date('Ymdhis');
                    $file_name = $str . $clientName;
                    //            dd($savePath);
                    $file->move($savePath, $file_name);
                    $fullpath = $savePath . $file_name;
                    $urlpath = $path.$file_name;
                    $access_token = $token_res['AuthorizerAccessToken'];
                    $res = app('Material')->addImage($access_token,$urlpath);
                    if(file_exists($fullpath)){
                        @unlink($fullpath);
                    }
                    if($res['code']==400){
                        return $res;
                    }
                }
                return $res['msg'];
            }else{
                return [
                    'code' => '400',
                    'msg'  => '未获取到文件'
                ];
            }
        }
        return $token_res;
    }
    //下载图片素材返回路径
    public function downImgFile(Request $request){
        $media_id = $request->input('media_id');
        $token_res = $this->getAuthToken();
        if($token_res['code']==200){
            $access_token = $token_res['AuthorizerAccessToken'];
            $res = app('JSSDK')->getMaterialByMediaId($access_token,$media_id);
            $res1 = app('Material')->savePic($res);
            return $res1;
        }
    }

    //语音消息
    public function voiceIndex(){

    }
    //视频消息
    public function videoIndex(){

    }
    //删除素材
    public function delMaterial(Request $request){
        $token_res = $this->getAuthToken();
        if($token_res['code']==200){
            $media_id = $request->input('media_id');
            $access_token = $token_res['AuthorizerAccessToken'];
            $res = app('JSSDK')->delMaterial($access_token,$media_id);
            $res_arr = json_decode($res,True);
            if($res_arr['errcode']!=0){
                return [
                    'code' => 400,
                    'msg'  => '删除永久图片素材失败,错误码：'.$res_arr['errcode'].'错误信息：'.$res_arr['errmsg']
                ];
            }else{
                return [
                    'code' => 200,
                    'msg'  => '删除素材成功'
                ];
            }
        }else{

        }
        return $token_res;
    }
    /*
     * 群发图文
     */
    public function sendAllNews(Request $request){
        $token_res = $this->getAuthToken();
        if($token_res['code']==200){
            $media_id = $request->input('media_id');
            $thumb_url_str = $request->input('thumb_url_str');
            $url_str = $request->input('url_str');
            $title_str = $request->input('title_str');
            $access_token = $token_res['AuthorizerAccessToken'];
            $res = app('JSSDK')->sendallNews($access_token,$media_id);
            $res_arr = json_decode($res,True);
            if($res_arr['errcode']!=0){
                return [
                    'code' => 400,
                    'msg'  => '群发图文消息失败,错误码：'.$res_arr['errcode'].'错误信息：'.$res_arr['errmsg']
                ];
            }else{
                $app_id = session('admin_user_info.app_id');
                $msg_data_id = $res_arr['msg_data_id'];
                $msg_id = $res_arr['msg_id'];
                app('Material')->addHistoryMsg($app_id,$msg_id,$msg_data_id,$media_id,$thumb_url_str,$url_str,$title_str);
                return [
                    'code' => 200,
                    'msg'  => '群发图文消息成功'
                ];
            }
        }
        return $token_res;
    }
    //添加定时发送任务
    public function timeSendNews(Request $request)
    {
        $media_id = $request->input('media_id');
        $thumb_url_str = $request->input('thumb_url_str');
        $url_str = $request->input('url_str');
        $title_str = $request->input('title_str');
        $day = $request->input('day');
        $hour = $request->input('hour');
        $min = $request->input('min');
        $app_id = session('admin_user_info.app_id');
        if($day>0){
            $day = date('d',time())+$day;
            $date_str = date('Y-m-',time()).$day.' '.$hour.':'.$min;
        }else{
            $date_str = $hour.':'.$min;
        }
        $send_time = strtotime($date_str);
        if($send_time-time()>300){
            $res = app('Material')->addSendNewTask($app_id,$send_time,$media_id,$thumb_url_str,$url_str,$title_str);
            return $res;
        }else{
            return [
                'code' => 400,
                'msg' => '定时时间至少需要在五分钟之后'
            ];
        }
    }
    /*
     * 发送微信号预览
     */
    public function previewNews(Request $request){
        $token_res = $this->getAuthToken();
        if($token_res['code']==200){
            $wx_name = $request->input('wx_name');
            $media_id = $request->input('media_id');
            $access_token = $token_res['AuthorizerAccessToken'];
            $res = app('Material')->previewNewMaterial($access_token,$media_id,$wx_name);
            return $res;
        }
        return $token_res;
    }
    /*
     * 编辑图文页面
     */
    public function editNews(Request $request){
        $token_res = $this->getAuthToken();
        if($token_res['code']==200){
            $media_id = $request->query('media_id');
            $thumb_url = $request->query('thumb_url');
            $thumb_url_arr = explode('|',$thumb_url);
            $access_token = $token_res['AuthorizerAccessToken'];
            $res = app('JSSDK')->getMaterialByMediaId($access_token,$media_id);
            $res_arr = json_decode($res,True);
            if(array_key_exists('errcode',$res_arr)){
                echo "<script>alert('".$res['msg']."')</script>";
            }else{
                $arr = json_decode($res,True);
                $news_item_arr = $arr['news_item'];
                foreach ($news_item_arr as $key=>$vo){
                    $news_item_arr[$key]['thumb_url'] = $thumb_url_arr[$key];
//                    $news_item_arr[$key]['content'] = str_replace('&quot;','"',str_replace('data-src','src',$vo['content']));
                    $news_item_arr[$key]['content'] = str_replace('&quot;',"'",str_replace('data-src','src',$vo['content']));
                }
                return view('material.newsEdit',['news_item_arr'=>$news_item_arr,'media_id'=>$media_id]);
            }
        }else{
            echo "<script>alert('".$token_res['msg']."')</script>";
        }
    }
    public function editNewsPost(Request $request){
        $token_res = $this->getAuthToken();
        if($token_res['code']==200){
            $access_token = $token_res['AuthorizerAccessToken'];
            $news_str = $request->input('news_str');
            $media_id = $request->input('media_id');
            $list_arr = json_decode($news_str,true);
            foreach ($list_arr as $key=>$vo){
                //需要下载的图片
                $multi_down_arr = [];
                //需要上传的图片
                $multi_upload_arr = [];
                //需要替换的图片
                $replace_arr = [];
                //替换图片的数组
                $img_replace_arr = [];

                //所有img图片和背景图片
                $img_arr = [];
                $content = $vo['content'];
                $preg = '/background-image:\s*url\(\S+\)/';
                preg_match_all($preg,$content,$match);
                //循环匹配到的背景图片
                foreach ($match[0] as $pic){
                    $arr = explode('&quot;',$pic);
                    $img_arr[] = $arr[1];
                }
                //所有的img图片
                $dom = \phpQuery::newDocumentHTML($content);
                $img_ele = pq("img");
                foreach ($img_ele as $v){
                    $vo_src = pq($v)->attr('src');
                    $img_arr[] = $vo_src;
                }
                foreach ($img_arr as $url){
                    if($url && strpos($url,'https://mmbiz.qpic.cn')===false){
                        if(strpos($url,'http')!==false){
                            $multi_down_arr[] = $url;
                        }else{
                            $multi_upload_arr[] = $url;
                        }
                    }
                }
                $replace_arr = array_merge($multi_upload_arr,$multi_down_arr);
                //下载远程图片到本地
                if($multi_down_arr){
                    $down_path_arr = app('JSSDK')->multiDownImage($multi_down_arr,100);
                    foreach ($down_path_arr as $host_path){
                        $multi_upload_arr[] = $host_path;
                    }
                }
                //上传到微信
                if($multi_upload_arr){
                    $uploaded_arr = app('JSSDK')->multiUploadImage($access_token,$multi_upload_arr);
                    foreach ($uploaded_arr as $wx_path){
                        $arr = json_decode($wx_path,true);
                        if(isset($arr) && array_key_exists('url',$arr)){
                            $new_url = json_decode($wx_path,true)['url'];
                        }else{
                            $new_url = '';
                        }
                        $img_replace_arr[] = $new_url;
                    }
                }

                $new_content = str_replace($replace_arr,$img_replace_arr,$content);
                $vo['content'] = $new_content;
                $arr = [
                    'media_id'=> $media_id,
                    'index' => $key,
                    'articles' => $vo
                ];
                $update_json = json_encode($arr,JSON_UNESCAPED_UNICODE);
//                dd($update_json);
                $res = app('JSSDK')->updateNews($access_token,$update_json);
                $res_arr = json_decode($res,True);
                if($res_arr['errcode']!=0){
                    return [
                        'code' => 400,
                        'msg'  => '更新图文消息失败,错误码：'.$res_arr['errcode'].'错误信息：'.$res_arr['errmsg']
                    ];
                }
            }
            return [
                'code' => 200,
                'msg'  => '更新图文消息成功'
            ];
        }
        return $token_res;
    }
    //查询是否添加了群发任务
    public function hadTaskNews(Request $request){
        $media_id = $request->input('media_id');
        $app_id = session('admin_user_info.app_id');
        $res = app('Material')->hasSendTask($app_id,$media_id);
        return $res;
    }
}
