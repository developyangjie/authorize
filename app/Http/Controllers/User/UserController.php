<?php
/**
 * Created by PhpStorm.
 * User  :  沈强
 * Date  :  2018/3/22
 * Time  :  14:55
 * Email :  736025986@qq.com
 */

namespace App\Http\Controllers\User;


use App\Http\Requests\Login;
use App\Http\Requests\UpdatePassword;
use Illuminate\Http\Request;

class UserController
{
    /*
     * 登录页面
     */
    public function login(){
        return view('login.login');
    }
    /*
     * 登录
     */
    public function loginPost(Login $request){
        $data = $request->all();
        $res = app('User')->login($data['username'],$data['password']);
        if($res['code']==200){
            session(['admin_user_info'=>$res['data']]);
            unset($res['data']);
        }
        return $res;
    }
    /*
     * 退出
     */
    public function loginOut(Request $request){
        $request->session()->flush();
        return redirect()->route('login');
    }
    //修改密码
    public function updatePwd(){
        return view('setting.updatePwd');
    }
    //修改密码
    public function updatePwdPost(UpdatePassword $request){
        $password = $request->input('password');
        $password2 = $request->input('password2');

        $wx_id = session('admin_user_info.wx_id');
        $userInfo = app('User')->getAppInfo($wx_id);
        if(!password_verify($password,$userInfo['password'])){
            return ['code' => 400,'msg' => '原密码错误'];
        }

        $result = app('User')->updateUserPwd($password2,$wx_id);
        return $result;
    }
}