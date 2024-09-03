<?php

namespace App\Requests\UnitOfMeasure;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UnitOfMeasureStoreRequest extends FormRequest
{
    public function authorize() : bool {
        return true;
    }

    public function rules() : array {
        return [
            'name' => 'required|unique:App\Models\UnitOfMeasure,name',
        ];
    }

    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'messages' => $validator->errors()
        ], 403));
    }
}
