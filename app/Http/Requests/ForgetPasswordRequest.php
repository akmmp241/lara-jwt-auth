<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ForgetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "email" => ["required", "email", Rule::exists("users", "email")]
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            "success" => false,
            "message" => "Email cannot be found",
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(response()->json([
            "success" => false,
            "message" => "You are already logged in",
        ], Response::HTTP_UNAUTHORIZED));
    }
}
