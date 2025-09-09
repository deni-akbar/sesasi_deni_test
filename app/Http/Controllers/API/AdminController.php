<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterVerifierRequest;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $service;

    public function __construct(AdminService $service)
    {
        $this->service = $service;
    }

    public function allUsers()
    {
        $users = $this->service->getAllUsers();
        return response()->json($users);
    }

    public function registerVerifier(RegisterVerifierRequest $request)
    {
        $user = $this->service->createVerifier($request->validated());
        return response()->json(['message' => 'Verifier created', 'user' => $user], 201);
    }

    public function promoteToVerifier(Request $request, $id)
    {
        $user = $this->service->promoteToVerifier($id);
        return response()->json(['message' => 'User promoted to verifikator', 'user' => $user]);
    }

    public function viewLeaveRequests(Request $request)
    {
        $result = $this->service->getAllLeaveRequests($request->query('status'));
        if (count($result) === 0) {
            return response()->json(['message' => 'No leave requests found'], 404);
        }
        return response()->json($result);
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);
        $this->service->resetPassword($id, $request->password);
        return response()->json(['message' => 'Password reset successfully']);
    }
}
