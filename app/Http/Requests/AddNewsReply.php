<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddNewsReply extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'  => 'required',
            'pic_url'  => 'required|url',
            'description'  => 'required',
            'url'  => 'url',
        ];
    }
    public function messages(){
        return [
            'title.required' => '请输入标题',
            'pic_url.required' => '请填写图片地址',
            'pic_url.url' => '请填写正确的图片地址',
            'description.required' => '请填写图文描述',
            'url.url' => '请输入正确的路径',
        ];
    }
}
