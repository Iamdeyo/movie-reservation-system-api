<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Check if the request is for login or registration
        if ($this->is('api/auth/login')) {
            return [
                'email' => 'required|email',
                'password' => 'required|string',
            ];
        } else {
            // Default to registration rules
            return [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed',
            ];
        }
    }

    protected function prepareForValidation(): void
    {
        // Convert camelCase input to snake_case if needed
        if ($this->has('passwordConfirmation')) {
            $this->merge([
                'password_confirmation' => $this->passwordConfirmation,
            ]);
        }
    }
}
