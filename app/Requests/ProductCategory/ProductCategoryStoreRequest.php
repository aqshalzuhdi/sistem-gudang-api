<?php

namespace App\Requests\ProductCategory;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductCategoryStoreRequest extends FormRequest
{
    public function authorize() : bool {
        return true;
    }

    public function rules() : array {
        return [
            'name' => 'required',
            'description' => 'required',
        ];
    }

    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'messages' => $validator->errors()
        ], 403));
    }
}
