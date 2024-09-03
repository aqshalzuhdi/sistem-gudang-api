<?php

namespace App\Interfaces;

interface ProductRepositoryInterface
{
    public function all($request);
    public function find($id);
    public function store(array $data);
    public function update(array $data, $id);
    public function delete($id);
}
