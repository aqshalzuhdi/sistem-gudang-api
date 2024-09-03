<?php

namespace App\Http\Controllers;

use App\Foglobal\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Interfaces\ProductCategoryRepositoryInterface;
use App\Requests\ProductCategory\ProductCategoryStoreRequest;
use App\Requests\ProductCategory\ProductCategoryUpdateRequest;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    private ProductCategoryRepositoryInterface $productCategoryRepositoryInterface;

    public function __construct(ProductCategoryRepositoryInterface $repositoryInterface) {
        $this->productCategoryRepositoryInterface = $repositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->productCategoryRepositoryInterface->all($request);
        return ProductCategoryResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCategoryStoreRequest $request)
    {
        $data = [
            'name' => $request->name,
            'description' => $request->description
        ];

        try {
            $query = $this->productCategoryRepositoryInterface->store($data);
            return ApiResponse::sendResponse(new ProductCategoryResource($query), 201);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->productCategoryRepositoryInterface->find($id);
        return ApiResponse::sendResponse(new ProductCategoryResource($data), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCategoryUpdateRequest $request, string $id)
    {
        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        try {
            $query = $this->productCategoryRepositoryInterface->update($data, $id);
            if($query) return ApiResponse::sendResponse('Product updated!', 200);

            return ApiResponse::sendResponse('Product update failed!', 403);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->productCategoryRepositoryInterface->delete($id);
        return ApiResponse::sendResponse('Product deleted!', 204);
    }
}
