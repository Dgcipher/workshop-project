<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class SearchPostRequest extends FormRequest
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

    /**
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        if (!empty($errors)) {
            $errorMessages = [];
            foreach ($errors as $field => $message) {
                $errorMessages[] = [
                    $field => $message[0]
                ];
            }
            throw new HttpResponseException(
                response()->json([
                    'status' => 'Validation Error',
                    'message' => $errorMessages

                ], JsonResponse::HTTP_BAD_REQUEST)
            );
        }
    }
}
