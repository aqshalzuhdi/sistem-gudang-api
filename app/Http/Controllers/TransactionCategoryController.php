<?php

namespace App\Http\Controllers;

use App\Foglobal\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionCategoryResource;
use App\Interfaces\TransactionCategoryRepositoryInterface;
use App\Requests\TransactionCategory\TransactionCategoryStoreRequest;
use App\Requests\TransactionCategory\TransactionCategoryUpdateRequest;
use Illuminate\Http\Request;

class TransactionCategoryController extends Controller
{
    private TransactionCategoryRepositoryInterface $transactionCategoryRepositoryInterface;

    public function __construct(TransactionCategoryRepositoryInterface $repositoryInterface) {
        $this->transactionCategoryRepositoryInterface = $repositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->transactionCategoryRepositoryInterface->all($request);
        return TransactionCategoryResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionCategoryStoreRequest $request)
    {
        $data = [
            'key_identifier' => $request->key_identifier,
            'name' => $request->name,
        ];

        try {
            $query = $this->transactionCategoryRepositoryInterface->store($data);
            return ApiResponse::sendResponse(new TransactionCategoryResource($query), 201);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->transactionCategoryRepositoryInterface->find($id);
        return ApiResponse::sendResponse(new TransactionCategoryResource($data));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionCategoryUpdateRequest $request, string $id)
    {
        $data = [
            'key_identifier' => $request->key_identifier,
            'name' => $request->name,
        ];

        try {
            $query = $this->transactionCategoryRepositoryInterface->update($data, $id);
            if($query) return ApiResponse::sendResponse('Transaction Category updated!', 200);

            return ApiResponse::sendResponse('Transaction Category update failed!', 403);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->transactionCategoryRepositoryInterface->delete($id);
        return ApiResponse::sendResponse('Transaction Category deleted!', 204);
    }
}
