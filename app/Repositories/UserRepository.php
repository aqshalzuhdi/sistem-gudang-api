<?php

namespace App\Repositories;
use App\Models\User;
use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all() {
        return User::all();
    }

    public function find($id) {
        return User::findOrFail($id);
    }

    public function store(array $data) {
        return User::create($data);
    }

    public function update(array $data, $id) {
        return User::whereId($id)->update($data);
    }

    public function delete($id) {
        return User::destroy($id);
    }
}
