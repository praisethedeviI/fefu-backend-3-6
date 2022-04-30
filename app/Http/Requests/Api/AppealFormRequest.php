<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseAppealFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use function response;

class AppealFormRequest extends BaseAppealFormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
