<?php

namespace App\Requests\Transaction;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionUpdateRequest extends FormRequest
{
    public function authorize() : bool {
        return true;
    }

    public function rules() : array {
        return [
            // 'inventory_id' => 'required|integer|exists:inventories,id',
            'transaction_category_id' => 'required|integer|exists:transaction_categories,id',
            // 'user_id' => 'required|integer|exists:users,id',
            // 'date' => 'required|date',
            'qty' => 'required|integer',
        ];
    }

    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'messages' => $validator->errors()
        ], 403));
    }
}
