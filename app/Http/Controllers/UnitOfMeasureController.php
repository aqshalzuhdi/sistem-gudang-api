<?php

namespace App\Http\Controllers;

use App\Foglobal\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UnitOfMeasureResource;
use App\Interfaces\UnitOfMeasureRepositoryInterface;
use App\Requests\UnitOfMeasure\UnitOfMeasureStoreRequest;
use App\Requests\UnitOfMeasure\UnitOfMeasureUpdateRequest;
use Illuminate\Http\Request;

class UnitOfMeasureController extends Controller
{
    private UnitOfMeasureRepositoryInterface $unitOfMeasureRepositoryInterface;

    public function __construct(UnitOfMeasureRepositoryInterface $repositoryInterface) {
        $this->unitOfMeasureRepositoryInterface = $repositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->unitOfMeasureRepositoryInterface->all($request);
        return UnitOfMeasureResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitOfMeasureStoreRequest $request)
    {
        $data = [
            'name' => $request->name,
        ];

        try {
            $query = $this->unitOfMeasureRepositoryInterface->store($data);
            return ApiResponse::sendResponse(new UnitOfMeasureResource($query), 201);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->unitOfMeasureRepositoryInterface->find($id);
        return ApiResponse::sendResponse(new UnitOfMeasureResource($data));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitOfMeasureUpdateRequest $request, string $id)
    {
        $data = [
            'name' => $request->name,
        ];

        try {
            $query = $this->unitOfMeasureRepositoryInterface->update($data, $id);
            if($query) return ApiResponse::sendResponse('Unit Of Measure updated!', 200);
            
            return ApiResponse::sendResponse('Unit Of Measure update failed!', 403);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->unitOfMeasureRepositoryInterface->delete($id);
        return ApiResponse::sendResponse('Unit Of Measure deleted!', 204);
    }
}
