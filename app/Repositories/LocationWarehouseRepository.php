<?php

namespace App\Repositories;

use App\Interfaces\LocationWarehouseRespositoryInterface;
use App\Models\LocationWarehouse;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class LocationWarehouseRepository implements LocationWarehouseRespositoryInterface
{
    public function all($request) {
        $perPage = 10;
        if($request->has('page') && $request->has('per_page')) {
            $perPage = $request->input('per_page');
        }

        $query = (new LocationWarehouse)->newQuery();

        if($request->has('search')) {
            $query->where(function($query) use ($request) {
                $kywd = '%' . $request->input('search') . '%';
                $query->where('name', 'LIKE', $kywd);
                $query->orWhere('address', 'LIKE', $kywd);
            });
        }

        $data = QueryBuilder::for($query)
            ->allowedSorts([
                AllowedSort::field('id'),
            ]);

        return $data->paginate($perPage);
    }

    public function find($id) {
        return LocationWarehouse::findOrFail($id);
    }

    public function store(array $data) {
        return LocationWarehouse::create($data);
    }

    public function update(array $data, $id) {
        return LocationWarehouse::whereId($id)->update($data);
    }

    public function delete($id) {
        return LocationWarehouse::destroy($id);
    }
}
