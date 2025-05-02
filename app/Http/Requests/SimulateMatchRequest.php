<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimulateMatchRequest extends FormRequest
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
            'homeTeamId' => 'required|exists:teams,id',
            'awayTeamId' => 'required|exists:teams,id',
        ];
    }

    public function messages(): array
    {
        return [
            'homeTeamId.required' => 'Home team is required',
            'awayTeamId.required' => 'Away team is required',
            'homeTeamId.exists' => 'Home team does not exist',
            'awayTeamId.exists' => 'Away team does not exist',
        ];
    }
}
