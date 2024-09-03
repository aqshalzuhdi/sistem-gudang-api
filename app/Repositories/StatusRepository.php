<?php

namespace App\Repositories;

use App\Interfaces\StatusRepositoryInterface;
use App\Models\Status;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class StatusRepository implements StatusRepositoryInterface
{
    public function all($request) {
        $perPage = 10;
        if($request->has('page') && $request->has('per_page')) {
            $perPage = $request->input('per_page');
        }

        $query = (new Status)->newQuery();

        if($request->has('search')) {
            $query->where(function($query) use ($request) {
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
        return Status::findOrFail($id);
    }

    public function store(array $data) {
        return Status::create($data);
    }

    public function update(array $data, $id) {
        return Status::whereId($id)->update($data);
    }

    public function delete($id) {
        return Status::destroy($id);
    }
}
