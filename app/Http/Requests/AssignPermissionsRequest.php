<?php

namespace App\Http\Requests;

use App\Enums\PrivacyEnums;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AssignPermissionsRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'privacy' => [
                'required',
                'string',
                'in:' . implode(',', PrivacyEnums::listConstants()),
                function ($attribute, $value, $fail) use ($id) {
                    if (Permission::where('role_id',$id)->where('privacy',$value)->exists()) {
                        $fail('The privacy is already assigned.');
                    }
                },
            ],
            'capabilities' => 'required|array',
            'capabilities.*' => 'required|string|in:'.implode(',', PrivacyEnums::getCapabilities($this->privacy)),
        ];
    }

    /**
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'data' => [],
            'message' => 'Validation Error',
            'errors' => $validator->messages()->all(),
        ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);

        throw new ValidationException($validator, $response);
    }
}
