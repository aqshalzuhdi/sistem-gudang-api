<?php

namespace App\Interfaces;

interface LocationWarehouseRespositoryInterface
{
    public function all($request);
    public function find($id);
    public function store(array $data);
    public function update(array $data, $id);
    public function delete($id);
}
