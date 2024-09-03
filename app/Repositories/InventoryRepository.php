<?php

namespace App\Repositories;

use App\Interfaces\InventoryRepositoryInterface;
use App\Models\Inventory;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function all($request) {
        $perPage = 10;
        if($request->has('page') && $request->has('per_page')) {
            $perPage = $request->input('per_page');
        }

        $query = (new Inventory)->newQuery();

        if($request->has('search')) {
            $query->where(function($query) use ($request) {
                $kywd = '%' . $request->input('search') . '%';
                $query->whereHas('product', function($query) use($kywd) {
                    $query->where('name', 'LIKE', $kywd);
                });
                $query->orWhere('batch_number', 'LIKE', $kywd);
                $query->orWhere('serial_number', 'LIKE', $kywd);
            });
        }

        $data = QueryBuilder::for($query, $request)
            ->allowedFilters([
                AllowedFilter::exact('batch_number', 'batch_number'),
            ])
            ->allowedSorts([
                AllowedSort::field('id'),
            ]);

        return $data->paginate($perPage);
    }

    public function find($id) {
        return Inventory::findOrFail($id);
    }

    public function store(array $data) {
        return Inventory::create($data);
    }

    public function update(array $data, $id) {
        return Inventory::whereId($id)->update($data);
    }

    public function delete($id) {
        return Inventory::destroy($id);
    }
}
