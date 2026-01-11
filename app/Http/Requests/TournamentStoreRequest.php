<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TournamentStoreRequest extends FormRequest
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
            'naziv' => ['required', 'string', 'max:255'],
            'datum_pocetka' => ['required'],
            'datum_zavrsetka' => ['required'],
            'broj_timova' => ['required', 'integer'],
            'lokacija' => ['required', 'string', 'max:255'],
        ];
    }
}
