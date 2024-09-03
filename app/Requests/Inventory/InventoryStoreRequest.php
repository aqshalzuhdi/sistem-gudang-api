<?php

namespace App\Requests\Inventory;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class InventoryStoreRequest extends FormRequest
{
    public function authorize() : bool {
        return true;
    }

    public function rules() : array {
        return [
            'product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id')->whereNull('deleted_at'),
            ],
            'status_id' => 'required|integer|exists:statuses,id',
            'batch_number' => 'required',
            'serial_number' => 'required',
            'qty' => 'required|integer',
            'price' => 'required|integer',
            'production_date' => 'required|date',
            'expiration_date' => 'nullable|date',
            'warranty_period' => 'nullable',
        ];
    }

    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'messages' => $validator->errors()
        ], 403));
    }
}
