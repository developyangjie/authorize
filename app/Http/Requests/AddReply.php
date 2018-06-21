<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddReply extends FormRequest
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
            'match_type'  => 'required',
            'key_word'  => 'required',
        ];
    }
    public function messages(){
        return [
            'match_type.required' => '请选择匹配类型',
            'key_word.required' => '请填写关键词'
        ];
    }
}
