<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class blogRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
       'title'=>'required|unique:blogs|max:255',
       'content'=>'required',
       'description'=>'required'
        ];
    }
}
