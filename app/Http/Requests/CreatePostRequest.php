<?php

namespace App\Http\Requests;

class CreatePostRequest extends ApiRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|regex:/^[a-zA-Z]+$/|max:255|unique:posts,title',
            'description' => 'required|min:3|max:200',
            'image' => 'required|image|mimes:png,jpg,svg,pneg,gif|max:2048'

        ];
    }
}
