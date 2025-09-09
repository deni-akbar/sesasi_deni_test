<?php

namespace App\Repositories;

use App\Models\LeaveRequest;

class LeaveRepository implements LeaveRepositoryInterface
{
    public function getAllWithUser()
    {
        return LeaveRequest::with('user')->get();
    }
}
