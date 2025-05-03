<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreRegistrationRequest extends FormRequest
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
        return [
            'email' => ['required', Rule::email()->rfcCompliant(false)->validateMxRecord(), 'unique:users,email'],
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()->symbols()],
        ];
    }
}
