<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ProductRepository implements ProductRepositoryInterface
{
    public function all($request) {
        $perPage = 10;
        if($request->has('page') && $request->has('per_page')) {
            $perPage = $request->input('per_page');
        }

        $query = (new Product)->newQuery();

        if($request->has('search')) {
            $query->where(function($query) use ($request) {
                $kywd = '%' . $request->input('search') . '%';
                $query->whereHas('product_category', function($query) use($kywd) {
                    $query->where('name', 'LIKE', $kywd);
                });
                $query->orWhereHas('location_warehouse', function($query) use($kywd) {
                    $query->where('name', 'LIKE', $kywd);
                });
                $query->orWhereHas('supplier', function($query) use($kywd) {
                    $query->where('name', 'LIKE', $kywd);
                });
                $query->orWhereHas('unit_of_measure', function($query) use($kywd) {
                    $query->where('name', 'LIKE', $kywd);
                });
                $query->orWhere('sku', 'LIKE', $kywd);
                $query->orWhere('name', 'LIKE', $kywd);
            });
        }

        $data = QueryBuilder::for($query)
            ->allowedSorts([
                AllowedSort::field('id'),
            ]);

        return $data->paginate($perPage);
    }

    public function find($id) {
        return Product::findOrFail($id);
    }

    public function store(array $data) {
        return Product::create($data);
    }

    public function update(array $data, $id) {
        return Product::whereId($id)->update($data);
    }

    public function delete($id) {
        return Product::destroy($id);
    }
}
