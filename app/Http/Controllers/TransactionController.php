<?php

namespace App\Http\Controllers;

use App\Foglobal\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Interfaces\InventoryRepositoryInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Requests\Inventory\InventoryStoreRequest;
use App\Requests\Transaction\TransactionStoreRequest;
use App\Requests\Transaction\TransactionUpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private TransactionRepositoryInterface $transactionRepositoryInterface;

    public function __construct(TransactionRepositoryInterface $repositoryInterface) {
        $this->transactionRepositoryInterface = $repositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->transactionRepositoryInterface->all($request);
        return TransactionResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionStoreRequest $trxRequest, InventoryStoreRequest $invRequest)
    {
        $data = [
            //transaction
            'inventory_id' => $trxRequest->inventory_id,
            'transaction_category_id' => $trxRequest->transaction_category_id,
            'user_id' => auth('api')->user()->id,
            'date' => Carbon::now(),
            'qty' => $trxRequest->qty,

            //inventory
            'product_id' => $invRequest->product_id,
            'status_id' => $invRequest->status_id,
            'batch_number' => $invRequest->batch_number,
            'serial_number' => $invRequest->serial_number,
            'qty' => $invRequest->qty,
            'price' => $invRequest->price,
            'production_date' => date('Y-m-d', strtotime($invRequest->production_date)),
            'expiration_date' => (!isset($invRequest->expiration_date)) ? null : date('Y-m-d', strtotime($invRequest->expiration_date)),
            'warranty_period' => $invRequest->warranty_period,
        ];

        try {
            $query = $this->transactionRepositoryInterface->store($data);
            return ApiResponse::sendResponse(new TransactionResource($query), 201);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->transactionRepositoryInterface->find($id);
        return ApiResponse::sendResponse(new TransactionResource($data), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionUpdateRequest $request, string $id)
    {
        $data = [
            'inventory_id' => $request->inventory_id,
            'transaction_category_id' => $request->transaction_category_id,
            'user_id' => auth('api')->user()->id,
            // 'date' => Carbon::now(),
            'qty' => $request->qty,
        ];

        try {
            $query = $this->transactionRepositoryInterface->update($data, $id);
            if($query) return ApiResponse::sendResponse('Transaction updated!', 200);

            return ApiResponse::sendResponse('Transaction update failed!', 403);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->transactionRepositoryInterface->delete($id);
        return ApiResponse::sendResponse('Transaction deleted!', 204);
    }
}
