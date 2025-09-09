<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CreateLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use App\Services\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\ResetUserPasswordRequest;

class UserController extends Controller
{

    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function createLeave(CreateLeaveRequest $request)
    {
        $validated = $request->validated();
        $leave = $this->service->createLeave($validated);
        return response()->json(['message' => 'Leave submitted', 'leave' => $leave], 201);
    }

    public function listLeaves()
    {
        $leaves = $this->service->listLeaves();
        if (count($leaves) === 0) {
            return response()->json(['message' => 'No leave requests found'], 404);
        }
        return response()->json($leaves);
    }

    public function showLeave($id)
    {
        $leave = $this->service->findLeave($id);
        if (!$leave) {
            return response()->json(['error' => 'Leave not found'], 404);
        }
        return response()->json($leave);
    }

    public function updateLeave(UpdateLeaveRequest $request, $id)
    {
        $result = $this->service->updateLeave($id, $request->validated());
        if ($result === 'not_found') {
            return response()->json(['error' => 'Leave not found'], 404);
        }
        if ($result === 'forbidden') {
            return response()->json(['error' => 'Cannot edit leave in its current status'], 403);
        }
        return response()->json(['message' => 'Leave updated', 'leave' => $result]);
    }

    public function cancelLeave($id)
    {
        $result = $this->service->cancelLeave($id);
        if ($result === 'not_found') {
            return response()->json(['error' => 'Leave not found'], 404);
        }
        if ($result === 'forbidden') {
            return response()->json(['error' => 'Cannot cancel leave in its current status'], 403);
        }
        return response()->json(['message' => 'Leave cancelled', 'leave' => $result]);
    }

    public function deleteLeave($id)
    {
        $leave = $this->service->deleteLeave($id);
        if (!$leave) {
            return response()->json(['error' => 'Leave not found'], 404);
        }
        if ($leave === 'forbidden') {
            return response()->json(['error' => 'Cannot delete leave in its current status'], 403);
        }
        return response()->json(['message' => 'Leave deleted']);
    }

    public function updatePassword(ResetUserPasswordRequest $request)
    {
        $validated = $request->validated();
        $result = $this->service->updatePassword($validated);
        if ($result === 'current_password_incorrect') {
            return response()->json(['error' => 'Current password is incorrect'], 403);
        }
        return response()->json(['message' => 'Password updated']);
    }
}
