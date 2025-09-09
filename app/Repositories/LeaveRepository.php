<?php

namespace App\Repositories;

use App\Models\LeaveRequest;

class LeaveRepository implements LeaveRepositoryInterface
{

    public function getAllWithUser($status)
    {
        $data = LeaveRequest::with('user');
        if ($status) {
            $data->where('status', $status);
        }
        return $data->get();
    }

    public function create(array $data)
    {
        return LeaveRequest::create($data);
    }
    public function find($id)
    {
        return LeaveRequest::find($id);
    }
    public function update(LeaveRequest $leaveRequest, array $data)
    {
        $leaveRequest->update($data);
        return $leaveRequest;
    }
    public function delete(LeaveRequest $leaveRequest)
    {
        return $leaveRequest->delete();
    }
    public function getByUserId($userId)
    {
        return LeaveRequest::where('user_id', $userId)->get();
    }
}
