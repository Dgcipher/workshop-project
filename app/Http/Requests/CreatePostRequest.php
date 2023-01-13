<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class CreatePostRequest extends FormRequest
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

      /**
     * @throws ValidationException
     */

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        if (!empty($errors)) {
            $errorMessages = [];
            foreach($errors as $field => $message) {
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
