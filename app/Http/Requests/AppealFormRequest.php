<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppealFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'regex:/^(\+?7|8)(( |\()?\d{3}\)?)( |-)?\d{3}( |-)?\d{2}( |-)?\d{2}$/'],
            'email' => ['nullable', 'email:rfc'],
            'message' => ['required', 'string', 'max:1000'],
        ];
    }
}
