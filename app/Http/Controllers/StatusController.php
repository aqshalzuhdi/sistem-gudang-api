<?php

namespace App\Http\Controllers;

use App\Foglobal\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\StatusResource;
use App\Interfaces\StatusRepositoryInterface;
use App\Repositories\StatusRepository;
use App\Requests\Status\StatusStoreRequest;
use App\Requests\Status\StatusUpdateRequest;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    private StatusRepositoryInterface $statusRepositoryInterface;

    public function __construct(StatusRepositoryInterface $repositoryInterface) {
        $this->statusRepositoryInterface = $repositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->statusRepositoryInterface->all($request);
        return StatusResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StatusStoreRequest $request)
    {
        $data = [
            'name' => $request->name,
        ];

        try {
            $query = $this->statusRepositoryInterface->store($data);
            return ApiResponse::sendResponse(new StatusResource($query), 201);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->statusRepositoryInterface->find($id);
        return ApiResponse::sendResponse(new StatusResource($data));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StatusUpdateRequest $request, string $id)
    {
        $data = [
            'name' => $request->name,
        ];

        try {
            $query = $this->statusRepositoryInterface->update($data, $id);
            if($query) return ApiResponse::sendResponse('Status updated!', 200);

            return ApiResponse::sendResponse('Status update failed!', 403);
        } catch (\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $this->statusRepositoryInterface->delete($id);
        return ApiResponse::sendResponse('Status cannot deleted!', 204);
    }
}
