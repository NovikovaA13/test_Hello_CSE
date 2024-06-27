<?php

namespace App\Http\Requests;

use App\Enums\StatutEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfilRequest extends FormRequest
{
    /**
     * Determine if the Admin is authorized to make this request.
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
            'nom' => 'bail|required|between:2,20',
            'prenom' => 'bail|required|between:2,20',
            'image' => 'bail|required|max:100',
            'statut' => [Rule::enum(StatutEnum::class)],
        ];
    }
}
