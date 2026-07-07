<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentRequest extends FormRequest
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
        if ($this->routeIs('equipment.index')) {
            return [
                'user_id' => ['required', 'integer', 'exists:users,user_id'],
            ];
        }

        return [
            'description' => ['required', 'string', Rule::unique('equipment', 'description')->ignore($this->equipment_id, 'equipment_id')],
            'serial_number' => ['required', 'string', Rule::unique('equipment', 'serial_number')->ignore($this->equipment_id, 'equipment_id')],
            'condition' => ['required', 'string', 'in:Working,Not Working'],
            'site_id' => ['required', 'exists:site,site_id'],
            'user_id' => ['required', 'integer', 'exists:users,user_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'site_id.exists' => 'The selected site does not exist.',
            'condition.in'   => 'Status of Equipment must be Working, Not Working',
            'description.unique' => 'This equipment has already been taken.', 
            'serial_number.unique' => 'This serial number must be unique per equipment.',
        ];
    }
}
