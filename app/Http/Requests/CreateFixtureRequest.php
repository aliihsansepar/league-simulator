<?php

namespace App\Http\Requests;

use App\Models\Fixture;
use Illuminate\Foundation\Http\FormRequest;

class CreateFixtureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // When the current fixture is not completed, we can't generate new fixtures
            $currentFixture = Fixture::with('match')->get();
            foreach ($currentFixture as $fixture) {
                if (
                    is_null($fixture->match) ||
                    ! $fixture->is_played
                ) {
                    $validator->errors()->add('week', 'Current fixture is not completed');
                }
            }
        });
    }
}
