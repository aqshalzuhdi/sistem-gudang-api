<?php

namespace App\Repositories;

use App\Interfaces\TransactionCategoryRepositoryInterface;
use App\Models\TransactionCategory;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class TransactionCategoryRepository implements TransactionCategoryRepositoryInterface
{
    public function all($request) {
        $perPage = 10;
        if($request->has('page') && $request->has('per_page')) {
            $perPage = $request->input('per_page');
        }

        $query = (new TransactionCategory)->newQuery();

        if($request->has('search')) {
            $query->where(function($query) use($request) {
                $kywd = '%' . $request->input('search') . '%';
                $query->where('name', 'LIKE', $kywd);
            });
        }

        $data = QueryBuilder::for($query)
            ->allowedSorts([
                    AllowedSort::field('id'),
                ]);

        return $data->paginate($perPage);
    }

    public function find($id) {
        return TransactionCategory::findOrFail($id);
    }

    public function store(array $data) {
        return TransactionCategory::create($data);
    }

    public function update(array $data, $id) {
        return TransactionCategory::whereId($id)->update($data);
    }

    public function delete($id) {
        return TransactionCategory::destroy($id);
    }
}
