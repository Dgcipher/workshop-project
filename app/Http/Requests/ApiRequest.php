<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;


abstract class ApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
   abstract public function authorize();


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
   abstract public function rules();

    /**
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'Validation Error',
                'message' => $validator->getMessageBag()->toArray(),

            ], JsonResponse::HTTP_BAD_REQUEST)
        );
    }

}
