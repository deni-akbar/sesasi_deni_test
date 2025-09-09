<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\LeaveRepositoryInterface;
use  \Illuminate\Support\Facades\Hash;

class UserService
{
    protected $users;
    protected $leaves;

    public function __construct(UserRepositoryInterface $users, LeaveRepositoryInterface $leaves)
    {
        $this->users = $users;
        $this->leaves = $leaves;
    }

    public function createLeave($validated)
    {
        $userId = JWTAuth::user()->id;
        $data = [
            'user_id' => $userId,
            'leave_type_id' => $validated['leave_type_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => 'submitted',
            'submitted_at' => now()
        ];

        // Handle file upload if present
        if (isset($validated['additional_file']) && $validated['additional_file'] instanceof \Illuminate\Http\UploadedFile) {
            $file = $validated['additional_file'];
            $uniqueName = uniqid('leave_', true) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('leave_files', $uniqueName, 'public');
            $data['additional_file'] = $path;
        }

        $leave = $this->leaves->create($data);
        return $leave;
    }

    public function listLeaves()
    {
        $userId = JWTAuth::user()->id;
        return $this->leaves->getByUserId($userId);
    }

    public function findLeave($leaveId)
    {
        $userId = JWTAuth::user()->id;
        $leave = $this->leaves->find($leaveId);
        if ($leave && $leave->user_id == $userId) {
            return $leave;
        }
        return null;
    }

    public function updateLeave($id, $validated)
    {
        $leave = $this->findLeave($id);
        if (!$leave) {
            return 'not_found';
        }
        if (!in_array($leave->status, ['submitted', 'revision'])) {
            return 'forbidden';
        }
        $this->leaves->update($leave, $validated);
        return $leave;
    }

    public function cancelLeave($id)
    {
        $userId = JWTAuth::user()->id;
        $leave = $this->leaves->find($id);
        if ($leave && $leave->user_id == $userId) {
            if (in_array($leave->status, ['approved', 'rejected', 'cancelled'])) {
                return 'forbidden';
            }
            $leave->status = 'cancelled';
            $leave->processed_at = now();
            $leave->save();
            return $leave;
        }
        return 'not_found';
    }

    public function deleteLeave($id)
    {
        $userId = JWTAuth::user()->id;
        $leave = $this->leaves->find($id);
        if ($leave && $leave->user_id == $userId) {
            if (!in_array($leave->status, ['submitted', 'revision', 'rejected', 'cancelled'])) {
                return 'forbidden';
            }
            $this->leaves->delete($leave);
            return true;
        }
        return 'not_found';
    }

    public function updatePassword($validated)
    {
        $user = JWTAuth::user();
        if (!Hash::check($validated['current_password'], $user->password)) {
            return 'current_password_incorrect';
        }
        $user->password = Hash::make($validated['password']);
        $user->save();
        return $user;
    }
}
