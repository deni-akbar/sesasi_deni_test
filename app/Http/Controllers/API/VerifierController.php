<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\ActOnLeaveRequest;
use App\Http\Requests\ListLeaveRequest;
use App\Http\Requests\ListUserRequest;
use App\Services\VerifierService;

class VerifierController extends Controller
{
    protected $service;

    public function __construct(VerifierService $service)
    {
        $this->service = $service;
    }

    public function listUsers(ListUserRequest $request)
    {
        $result = $this->service->getListUsers($request->verified);
        return response()->json($result);
    }

    public function verifyUser($id)
    {
        $result = $this->service->VerifyUser($id);
        if ($result === 'not_found') {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json(['message' => 'User verified', 'user' => $result]);
    }

    public function listLeaveRequests(ListLeaveRequest $request)
    {
        $result = $this->service->getLeaveRequestsFiltered($request->status);
        return response()->json($result);
    }

    public function actOnLeave(ActOnLeaveRequest $request, $id)
    {
        $validated = $request->validated();
        $result = $this->service->actOnLeave($id, $validated);
        if ($result === 'not_found') {
            return response()->json(['error' => 'Leave not found'], 404);
        }
        return response()->json(['message' => 'Action applied', 'leave' => $result]);
    }
}
