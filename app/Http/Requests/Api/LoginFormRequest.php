<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseLoginFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginFormRequest extends BaseLoginFormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 401));
    }
}
