<?php

namespace App\Requests\Product;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductUpdateRequest extends FormRequest
{
    public function authorize() : bool {
        return true;
    }

    public function rules() : array {
        return [
            'product_category_id' => 'required|integer|exists:product_categories,id',
            'location_warehouse_id' => 'required|integer|exists:location_warehouses,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'unit_of_measure_id' => 'required|integer|exists:unit_of_measures,id',
            'status_id' => 'required|integer|exists:statuses,id',
            'sku' => 'required|unique:products,sku,' . $this->route('product'),
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
