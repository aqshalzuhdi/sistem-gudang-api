<?php

namespace App\Repositories;

use App\Interfaces\ProductCategoryRepositoryInterface;
use App\Models\ProductCategory;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    public function all($request) {
        $perPage = 10;
        if($request->has('page') && $request->has('per_page')) {
            $perPage = $request->input('per_page');
        }

        $query = (new ProductCategory)->newQuery();

        if($request->has('search')){
            $query->where(function($query) use($request){
                $kywd = '%' . $request->input('search') . '%';
                $query->orWhere('name', 'LIKE', $kywd);
                $query->orWhere('description', 'LIKE', $kywd);
            });
        }

        $data = QueryBuilder::for($query)
            ->allowedSorts([
                    AllowedSort::field('id'),
                ]);

        return $data->paginate($perPage);
    }

    public function find($id) {
        return ProductCategory::findOrFail($id);
    }

    public function store(array $data) {
        return ProductCategory::create($data);
    }

    public function update(array $data, $id) {
        return ProductCategory::whereId($id)->update($data);
    }

    public function delete($id) {
        return ProductCategory::destroy($id);
    }
}
