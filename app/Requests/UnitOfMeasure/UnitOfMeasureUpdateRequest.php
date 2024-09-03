<?php

namespace App\Requests\UnitOfMeasure;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UnitOfMeasureUpdateRequest extends FormRequest
{
    public function authorize() : bool {
        return true;
    }

    public function rules() : array {
        // dd($this->route('unit_of_measure')); // Ini akan menampilkan parameter dari rute unit_of_measure

        return [
            'name' => [
                'required',
                Rule::unique('unit_of_measures')->ignore($this->route('unit_of_measure')),
            ],
        ];
    }

    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'messages' => $validator->errors()
        ], 403));
    }
}
