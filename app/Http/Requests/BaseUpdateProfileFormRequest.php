<?php

namespace App\Http\Requests;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class BaseUpdateProfileFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'name' => ['required', 'max:255', 'string'],
            'email' => ['required', 'email:rfc'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        /** @var User $user */
        $user = Auth::user();

        $validator->after(function (Validator $validator) use ($user) {
            $userWithEmailBuilder = User::query()
                ->where('email', $this->validated('email'))
                ->where('id', '<>', $user->id);
            if ($userWithEmailBuilder->exists()) {

                $validator->errors()->add('email', 'Email is already taken.');
            }
        });
    }
}
