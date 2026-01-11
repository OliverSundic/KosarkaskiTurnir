<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UtakmicaUpdateRequest extends FormRequest
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
            'tournament_id' => ['required', 'integer', 'exists:tournaments,id'],
            'domaci_tim_id' => ['required', 'integer', 'exists:teams,id'],
            'strani_tim_id' => ['required', 'integer', 'exists:teams,id'],
            'referee_id' => ['required', 'integer', 'exists:users,id'],
            'mesto' => ['required', 'string', 'max:255'],
            'rezultat' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:zakazana,u_toku,zavrsena,otkazana'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}
