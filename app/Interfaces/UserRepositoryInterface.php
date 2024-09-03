<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function all();
    public function find($id);
    public function store(array $data);
    public function update(array $data, $id);
    public function delete($id);
}
