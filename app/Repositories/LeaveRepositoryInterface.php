<?php

namespace App\Repositories;

use App\Models\LeaveRequest;

interface LeaveRepositoryInterface
{
    public function getAllWithUser($status);
    public function create(array $data);
    public function find($id);
    public function getByUserId($userId);
    public function delete(LeaveRequest $leaveRequest);
    public function update(LeaveRequest $leaveRequest, array $data);
}
