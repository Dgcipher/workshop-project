<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
        $image = request()->isMethod('put') ? 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';

        return [
            //
            'title'=> ['required','string','min:3','max:250'],
            'description' => ['required','string','min:20','max:250'],
            'image' => $image,
            'category_id' => 'required'
        ];
    }
}
