<?php

namespace App\Http\Controllers;

use App\Foglobal\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\InventoryResource;
use App\Interfaces\InventoryRepositoryInterface;
use App\Requests\Inventory\InventoryStoreRequest;
use App\Requests\Inventory\InventoryUpdateRequest;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    private InventoryRepositoryInterface $inventoryRepositoryInterface;

    public function __construct(InventoryRepositoryInterface $repositoryInterface) {
        $this->inventoryRepositoryInterface = $repositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->inventoryRepositoryInterface->all($request);
        return InventoryResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InventoryStoreRequest $request)
    {
        $data = [
            'product_id' => $request->product_id,
            'status_id' => $request->status_id,
            'batch_number' => $request->batch_number,
            'serial_number' => $request->serial_number,
            'qty' => $request->qty,
            'price' => $request->price,
            'production_date' => date('Y-m-d', strtotime($request->production_date)),
            'expiration_date' => date('Y-m-d', strtotime($request->expiration_date)),
            'warranty_perioed' => $request->warranty_period,
        ];

        try {
            $query = $this->inventoryRepositoryInterface->store($data);
            return ApiResponse::sendResponse(new InventoryResource($query), 201);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->inventoryRepositoryInterface->find($id);
        return ApiResponse::sendResponse(new InventoryResource($data));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventoryUpdateRequest $request, string $id)
    {
        $data = [
            'product_id' => $request->id,
            'status_id' => $request->id,
            'batch_number' => $request->batch_number,
            'serial_number' => $request->serial_number,
            'qty' => $request->qty,
            'price' => $request->price,
            'production_date' => $request->production_date,
            'expiration_date' => $request->expiration_date,
            'warranty_perioed' => $request->warranty_period,
        ];

        try {
            $query = $this->inventoryRepositoryInterface->update($data, $id);
            if($query) return ApiResponse::sendResponse('Inventory updated!', 200);

            return ApiResponse::sendResponse('Inventory update failed!', 403);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->inventoryRepositoryInterface->delete($id);
        return ApiResponse::sendResponse('Inventory deleted!', 204);
    }
}
