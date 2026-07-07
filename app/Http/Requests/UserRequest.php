<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->routeIs('user.index')) {
            return [
                'user_id' => ['required', 'integer', 'exists:users,user_id'],
            ];
        }

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'user_type' => ['required', 'string', 'in:Admin,SuperAdmin'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_type.in' => 'User Type must be Admin, SuperAdmin',
            'user_id.exists' => 'The selected user does not exist.',
        ];
    }
}
