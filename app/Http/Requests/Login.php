<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Login extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'  => 'bail|required',
            'password'  => 'required'
        ];
    }
    public function messages(){
        return [
            'user_name.required' => '请输入用户名',
            'password.required' => '请输入密码'
        ];
    }
}
