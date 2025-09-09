<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\RoleRepositoryInterface;
use App\Repositories\LeaveRepositoryInterface;

class VerifierService
{
    protected $users;
    protected $roles;
    protected $leaves;

    public function __construct(UserRepositoryInterface $users, RoleRepositoryInterface $roles, LeaveRepositoryInterface $leaves)
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->leaves = $leaves;
    }

    public function actOnLeave($id, $validated)
    {
        $leave = $this->leaves->find($id);
        if (!$leave) {
            return 'not_found';
        }
        $action = $validated['action'];
        if ($action === 'approve') {
            $leave->status = 'approved';
        } elseif ($action === 'reject') {
            $leave->status = 'rejected';
        } elseif ($action === 'revision') {
            $leave->status = 'revision';
        }

        $leave->comment = $validated['comment'];
        $leave->processed_by = JWTAuth::user()->id;
        $leave->processed_at = now();
        $leave->save();
        return $leave;
    }

    public function getLeaveRequestsFiltered($status)
    {
        return $this->leaves->getAllWithUser($status);
    }

    public function getListUsers($verified)
    {
        return $this->users->getUserVerified($verified);
    }

    public function VerifyUser($id)
    {
        $user = $this->users->find($id);
        if (!$user) {
            return "not_found";
        }
        $user->is_verified = true;
        $user->save();
        return $user;
    }
}
