<?php

namespace App\Http\Requests;

class SearchPostRequest extends ApiRequest
{
    private array $fields;

    public function __construct()
    {
        parent::__construct();
        $this->fields = ['title'];
    }
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
            'select' => 'sometimes|array|in:' . implode(',', $this->fields),
            'per_page' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
        ];
    }
}
