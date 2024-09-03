<?php

namespace App\Http\Controllers;

use App\Foglobal\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierResource;
use App\Interfaces\SupplierRepositoryInterface;
use App\Requests\Supplier\SupplierStoreRequest;
use App\Requests\Supplier\SupplierUpdateRequest;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    private SupplierRepositoryInterface $supplierRepositoryInterface;

    public function __construct(SupplierRepositoryInterface $repositoryInterface) {
        $this->supplierRepositoryInterface = $repositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->supplierRepositoryInterface->all($request);
        return SupplierResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierStoreRequest $request)
    {
        $data = [
            'name' => $request->name,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ];

        try {
            $query = $this->supplierRepositoryInterface->store($data);
            return ApiResponse::sendResponse(new SupplierResource($query), 201);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->supplierRepositoryInterface->find($id);
        return ApiResponse::sendResponse(new SupplierResource($data));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierUpdateRequest $request, string $id)
    {
        $data = [
            'name' => $request->name,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ];

        try {
            $query = $this->supplierRepositoryInterface->update($data, $id);
            if($query) return ApiResponse::sendResponse('Supplier updated!', 200);

            return ApiResponse::sendResponse('Supplier update failed!', 403);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->supplierRepositoryInterface->delete($id);
        return ApiResponse::sendResponse('Supplier deleted!', 204);
    }
}
