<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255", "min:2"],
            "email" => ["required", "email", Rule::unique('users', 'email')->ignore(Auth::id())],
            "user_id" => ["required", "numeric"]
        ];
    }

    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(response([
            "error" => [
                "message" => "You are not authorized to perform this action",
            ],
        ], Response::HTTP_FORBIDDEN));
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response([
            "errors" => $validator->errors(),
        ], Response::HTTP_BAD_REQUEST));
    }
}
