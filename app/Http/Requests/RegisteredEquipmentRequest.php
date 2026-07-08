<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisteredEquipmentRequest extends FormRequest
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
        if ($this->routeIs('registered_equipment.index')) {
            return [
                'site_id' => ['required', 'integer', 'exists:site,site_id'],
            ];
        }

        return [
            'site_id' => ['required', 'integer', 'exists:site,site_id'],
            'equipment_ids' => ['required', 'array'],
            'equipment_ids.*' => ['integer', 'distinct', 'exists:equipment,equipment_id']
        ];
    }
}
