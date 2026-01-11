<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamStoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'tournament_id' => ['required', 'integer', 'exists:tournaments,id'],
            'naziv' => ['required', 'string', 'max:255'],
            'grad' => ['required', 'string', 'max:100'],
            'broj_bodova' => ['required', 'integer'],
        ];
    }
}
