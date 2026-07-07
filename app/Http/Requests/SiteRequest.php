<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiteRequest extends FormRequest
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
        if ($this->routeIs('site.index')) {
            return [
                'user_id' => ['required', 'integer', 'exists:users,user_id'],
            ];
        }

        return [
            'active' => ['required', 'integer', 'in:1,0'],
            'description' => [ 'required', 'string', Rule::unique('site', 'description')->ignore($this->site_id, 'site_id')],
            'user_id' => ['required', 'integer', 'exists:users,user_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'active.in' => 'User Type must be Active, Inactive',
            'description.unique' => 'The site is already taken',
            'user_id.exists' => 'The selected user does not exist.',
        ];
    }
}
