<?php
/**
 * Created by PhpStorm.
 * User  :  沈强
 * Date  :  2018/3/26
 * Time  :  17:59
 * Email :  736025986@qq.com
 */

namespace App\Http\Controllers\Index;


use Illuminate\Http\Request;

class IndexController
{
    public $app_id;
    public $app_secret;

    function __construct()
    {
        $this->app_id=env('APP_XUET_ID');
        $this->app_secret=env('APP_XUET_SECRET');
    }

    public function index()
    {
        return view('index.index');
    }
    //历史消息post
    public function historyListPost(Request $request){
        $page = $request->input('page');
        $limit = 5;
        $app_id = session('admin_user_info.app_id');
        $total_num = app('Material')->totalHistoryNums($app_id);
        $total_page = ceil($total_num/$limit);
        if($page>$total_page){
            $page = $total_page;
        }
        if($page<1){
            $page = 1;
        }
        $history_List = app('Material')->historyList($app_id,$page,$limit);
        return $history_List;
    }
    //授权页面
    public function authView(){
        $app_id = $this->app_id;
        $app_secret = $this->app_secret;
        $res =app('Wx')->getPreAuthCode($app_id,$app_secret);
        if($res['code']==200){
            $pre_auth_code = $res['PreAuthCode'];
            $redirect_uri = route('getAuthCode');
            $format = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid=%s&pre_auth_code=%s&redirect_uri=%s&auth_type=%s';
            $auth_url =  sprintf($format,$app_id,$pre_auth_code,$redirect_uri,3);
        }else{
            $msg = $res['msg'];
            $auth_url = 'javascript:alert("'.$msg.'")';
        }
        return view('index.authView',[
            'auth_url'=>$auth_url
        ]);
    }
    //已授权公众号首页
    public function authIndex(Request $request){
        $wx_id = session('admin_user_info.wx_id');
        $app_id = session('admin_user_info.app_id');
        $authorization_code = session('admin_user_info.auth_code');
        $component_appid = $this->app_id;
        $component_secret = $this->app_secret;
        $res = app('Wx')->getAuthToken($wx_id,$component_appid,$component_secret,$authorization_code);
        if($res['code']==200){
            $access_token=$res['AuthorizerAccessToken'];
            //昨天
            $yesterday_time1 = strtotime(date('Y-m-d',time()));
            $yesterday_time2 = $yesterday_time1-24*3600;
            //前天
            $before_yesterday_time2 = $yesterday_time2-24*3600;
            $total_res = app('Fans')->getTotalFansNum($access_token);
            $total_nums = $total_res['msg'];
            //昨天新增人数
            $yesterday_fans_arr = app('Fans')->getAddFans($access_token,$yesterday_time1,$yesterday_time2);
            //前天新增
            $before_yesterday_fans_arr = app('Fans')->getAddFans($access_token,$yesterday_time2,$before_yesterday_time2);
            //已群发消息
            $limit = 5;
            $page = 1;
            $history_List = app('Material')->historyList($access_token,$app_id,$page,$limit);
            $total_num = app('Material')->totalHistoryNums($app_id);
            $total_page = ceil($total_num/$limit);
            return view('index.authIndex',[
//                'AuthorizerAccessToken'=>$access_token,
                'total_nums' => $total_nums,
                'yesterday_fans_arr'=>$yesterday_fans_arr,
                'before_yesterday_fans_arr'=>$before_yesterday_fans_arr,
                'history_List' => $history_List,
                'page' => $page,
                'total_page' => $total_page
            ]);
        }else{
            return '<script>alert("'.$res['msg'].'");location.href="'.route('authView').'"</script>';
        }
    }
    //获取自定义菜单列表
    public function getMenuList(){
        $wx_id = session('admin_user_info.wx_id');
        $app_id = session('admin_user_info.app_id');
        $authorization_code = session('admin_user_info.auth_code');
        $component_appid = $this->app_id;
        $component_secret = $this->app_secret;
        $res = app('Wx')->getMenuList($wx_id,$component_appid,$component_secret,$authorization_code);
        if(isset($res['code']) && $res['code']==400){
            $menu_list_json = '';
        }else{
            foreach ($res['menu']['button'] as $key0=>$v) {
                if (isset($v['type']) && $v['type'] == 'click') {
                    $key = $v['key'];
                    $res_key = app('Wx')->getTextByKey($key, $app_id);
                    if ($res_key['code'] == 200) {
                        $text = $res_key['msg'];
                    } else {
                        $text = '';
                    }
                    $res['menu']['button'][$key0]['text'] = $text;
                }elseif (isset($v['type']) && $v['type'] == 'media_id') {
                    $media_id = $v['media_id'];
                    $html = $this->getNewsHtml($media_id);
                    $res['menu']['button'][$key0]['news_html'] = $html;
                }elseif (isset($v['sub_button']) && count($v['sub_button'])>0){
                    foreach ($v['sub_button'] as $key1=>$v1){
                        if(isset($v1['type']) && $v1['type']=='click'){
                            $key = $v1['key'];
                            $res_key = app('Wx')->getTextByKey($key,$app_id);
                            if($res_key['code']==200){
                                $text = $res_key['msg'];
                            }else{
                                $text = '';
                            }
                            $res['menu']['button'][$key0]['sub_button'][$key1]['text']=$text;
                        }else if(isset($v1['type']) && $v1['type']=='media_id'){
                            //添加图文消息html
                            $media_id = $v1['media_id'];
                            $html = $this->getNewsHtml($media_id);
                            $res['menu']['button'][$key0]['sub_button'][$key1]['news_html']=$html;
                        }
                    }
                }
            }
            $menu_list_json = json_encode($res['menu']);
        }
//        $menu_list_json = '{"button":[{"type":"click","name":"今日歌曲","key":"V1001_TODAY_MUSIC"},{"name":"菜单","sub_button":[{"type":"view","name":"搜索","url":"http://www.soso.com/"},{"type":"miniprogram","name":"wxa","url":"http://mp.weixin.qq.com","appid":"wx286b93c14bbf93aa","pagepath":"pages/lunar/index"},{"type":"click","name":"赞一下我们","key":"V1001_GOOD"}]}]}';
        return view('index.menuList',['menu_list_json'=>$menu_list_json]);
    }
    /*
     * 获取图文素材HTML
     */
    public function getNewsHtml($media_id){
        $wx_id = session('admin_user_info.wx_id');
        $authorization_code = session('admin_user_info.auth_code');
        $component_appid = env('APP_XUET_ID');
        $component_secret = env('APP_XUET_SECRET');
        $token_res = app('Wx')->getAuthToken($wx_id,$component_appid,$component_secret,$authorization_code);
        $access_token = $token_res['AuthorizerAccessToken'];
        $result = app('JSSDK')->getMaterialByMediaId($access_token,$media_id);
        $result_arr = json_decode($result,true);
        $_html = '<dl>';
        foreach ($result_arr['news_item'] as $key=>$vo){
            if($key==0){
                $_html .=      '<dt>';
                $_html .=           '<a href="'.$vo['url'].'" target="_blank">';
                $_html .=           '<div class="pic">';
                $_html .=               '<img class="thumb" src="" alt="">';
                $_html .=           '</div>';
                $_html .=           '<span>'.$vo['title'].'</span>';
                $_html .=           '</a>';
                $_html .=       '</dt>';
            }else{
                $_html .=       '<dd>';
                $_html .=            '<a target="_blank" href="'.$vo['url'].'">';
                $_html .=            '<div class="tit">';
                $_html .=               '<span>'.$vo['title'].'</span>';
                $_html .=            '</div>';
                $_html .=            '<div class="pic">';
                $_html .=               '<img class="thumb" src="" alt="">';
                $_html .=            '</div>';
                $_html .=            '</a>';
                $_html .=       '</dd>';
            }
        }
        $_html .=     '</dl>';
        return $_html;
    }

    //创建自定义菜单
    public function createMenuList(Request $request){
        $wx_id = session('admin_user_info.wx_id');
        $authorization_code = session('admin_user_info.auth_code');
        $component_appid = $this->app_id;
        $component_secret = $this->app_secret;
        $data_json = $request->input('menu_json_str');
        $res = app('Wx')->createMenuList($wx_id,$component_appid,$component_secret,$authorization_code,$data_json);
        return $res;
    }
    //插入key—text对应值
    public function addKeyText(Request $requeset){
        $key = $requeset->input('key');
        $text = $requeset->input('text');
        $app_id = session('admin_user_info.app_id');
        $res = app('Wx')->addKeyText($key,$text,$app_id);
        return $res;
    }
}