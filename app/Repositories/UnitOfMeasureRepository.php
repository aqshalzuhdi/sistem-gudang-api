<?php

namespace App\Repositories;

use App\Interfaces\UnitOfMeasureRepositoryInterface;
use App\Models\UnitOfMeasure;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class UnitOfMeasureRepository implements UnitOfMeasureRepositoryInterface
{
    public function all($request) {
        $perPage = 10;
        if($request->has('page') && $request->has('per_page')) {
            $perPage = $request->input('per_page');
        }

        $query = (new UnitOfMeasure)->newQuery();

        if($request->has('search')){
            $query->where(function($query) use($request){
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
        return UnitOfMeasure::findOrFail($id);
    }

    public function store(array $data) {
        return UnitOfMeasure::create($data);
    }

    public function update(array $data, $id) {
        return UnitOfMeasure::whereId($id)->update($data);
    }

    public function delete($id) {
        return UnitOfMeasure::destroy($id);
    }
}
