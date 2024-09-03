<?php

namespace App\Http\Controllers;

use App\Foglobal\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\LocationWarehouseResource;
use App\Interfaces\LocationWarehouseRespositoryInterface;
use App\Models\LocationWarehouse;
use App\Requests\LocationWarehouse\LocationWarehouseStoreRequest;
use App\Requests\LocationWarehouse\LocationWarehouseUpdateRequest;
use Illuminate\Http\Request;

class LocationWarehouseController extends Controller
{
    private LocationWarehouseRespositoryInterface $locationWarehouseRepositoryInterface;

    public function __construct(LocationWarehouseRespositoryInterface $repositoryInterface) {
        $this->locationWarehouseRepositoryInterface = $repositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->locationWarehouseRepositoryInterface->all($request);
        return LocationWarehouseResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocationWarehouseStoreRequest $request)
    {
        $data = [
            'name' => $request->name,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'address' => $request->address,
        ];

        try {
            $query = $this->locationWarehouseRepositoryInterface->store($data);
            return ApiResponse::sendResponse(new LocationWarehouseResource($query), 201);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->locationWarehouseRepositoryInterface->find($id);
        return ApiResponse::sendResponse(new LocationWarehouseResource($data), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocationWarehouseUpdateRequest $request, string $id)
    {
        $data = [
            'name' => $request->name,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'address' => $request->address
        ];
        
        try {
            $query = $this->locationWarehouseRepositoryInterface->update($data, $id);
            if($query) return ApiResponse::sendResponse('Location Warehouse updated!', 200);

            return ApiResponse::sendResponse('Location Warehouse update failed!', 403);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->locationWarehouseRepositoryInterface->delete($id);
        return ApiResponse::sendResponse('Product deleted!', 204);
    }
}
