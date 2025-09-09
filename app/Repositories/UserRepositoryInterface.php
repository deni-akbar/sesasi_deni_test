<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function allWithRole();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
}
