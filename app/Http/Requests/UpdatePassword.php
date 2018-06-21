<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/8
 * Time: 14:01
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class UpdatePassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'password' => 'required',
            'password2' => 'required|min:6',
            'check_pwd' => 'required|same:password2'
        ];
    }
    //自定义信息
    public function messages()
    {
        return [
            'password.required' => '初始密码必填!',
            'password2.required'  => '新密码必填',
            'check_pwd.required' => '确认密码必填!',
            'password2.min' => '密码最短6个字符!',
            'check_pwd.same' => '两次密码输入不一致!'
        ];
    }
}