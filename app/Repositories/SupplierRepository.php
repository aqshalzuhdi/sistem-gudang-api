<?php

namespace App\Repositories;

use App\Interfaces\SupplierRepositoryInterface;
use App\Models\Supplier;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function all($request) {
        $perPage = 10;
        if($request->has('page') && $request->has('per_page')) {
            $perPage = $request->input('per_page');
        }

        $query = (new Supplier)->newQuery();

        if($request->has('search')) {
            $query->where(function($query) use ($request) {
                $kywd = '%' . $request->input('search') . '%';
                $query->where('name', 'LIKE', $kywd);
                $query->orWhere('address', 'LIKE', $kywd);
                $query->orWhere('phone_number', 'LIKE', $kywd);
                $query->orWhere('email', 'LIKE', $kywd);
            });
        }

        $data = QueryBuilder::for($query)
            ->allowedSorts([
                AllowedSort::field('id'),
            ]);

        return $data->paginate($perPage);
    }

    public function find($id) {
        return Supplier::findOrFail($id);
    }

    public function store(array $data) {
        return Supplier::create($data);
    }

    public function update(array $data, $id) {
        return Supplier::whereId($id)->update($data);
    }

    public function delete($id) {
        return Supplier::destroy($id);
    }
}
