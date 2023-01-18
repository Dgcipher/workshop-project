<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ValidationException;
class SearchArticleRequest extends FormRequest
{
    use ValidationException;
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
            'select' => 'sometimes|array',
            'per_page' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
        ];
    }

}
