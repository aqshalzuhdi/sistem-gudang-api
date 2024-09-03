<?php

namespace App\Http\Controllers;

use App\Foglobal\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Interfaces\ProductRepositoryInterface;
use App\Requests\Product\ProductStoreRequest;
use App\Requests\Product\ProductUpdateRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductRepositoryInterface $productRepositoryInterface;

    public function __construct(ProductRepositoryInterface $repositoryInterface) {
        $this->productRepositoryInterface = $repositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->productRepositoryInterface->all($request);
        return ProductResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $data = [
            'product_category_id' => $request->product_category_id,
            'location_warehouse_id' => $request->location_warehouse_id,
            'supplier_id' => $request->supplier_id,
            'unit_of_measure_id' => $request->unit_of_measure_id,
            'status_id' => $request->status_id,
            'sku' => $request->sku,
            'name' => $request->name,
            'description' => $request->description,
        ];

        try {
            $query = $this->productRepositoryInterface->store($data);
            return ApiResponse::sendResponse(new ProductResource($query), 201);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->productRepositoryInterface->find($id);
        return ApiResponse::sendResponse(new ProductResource($data), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $id)
    {
        $data = [
            'product_category_id' => $request->product_category_id,
            'location_warehouse_id' => $request->location_warehouse_id,
            'supplier_id' => $request->supplier_id,
            'unit_of_measure_id' => $request->unit_of_measure_id,
            'status_id' => $request->status_id,
            'sku' => $request->sku,
            'name' => $request->name,
            'description' => $request->description,
        ];

        try {
            $query = $this->productRepositoryInterface->update($data, $id);
            if($query) return ApiResponse::sendResponse('Product updated!', 200);

            return ApiResponse::sendResponse('Product update failed', 403);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->productRepositoryInterface->delete($id);
        return ApiResponse::sendResponse('Product deleted!', 204);
    }
}
