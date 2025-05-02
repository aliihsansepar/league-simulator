<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimulateWeekRequest extends FormRequest
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
            'week' => 'sometimes|integer|min:1|max:38',
        ];
    }

    public function messages(): array
    {
        return [
            'week.integer' => 'Week must be an integer',
            'week.min' => 'Week must be at least 1',
            'week.max' => 'Week must be at most 38',
        ];
    }
}
