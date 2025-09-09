<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifierController extends Controller
{
    // apply middleware role:verifikator
    public function listUsers(Request $request)
    {
        $filter = $request->query('verified'); // '1' or '0' or null
        $q = User::query();
        if ($filter !== null) {
            $q->where('is_verified', (bool)$filter);
        }
        return response()->json($q->get());
    }

    public function verifyUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->is_verified = true;
        $user->save();
        return response()->json(['message' => 'User verified', 'user' => $user]);
    }

    public function listLeaveRequests(Request $request)
    {
        $status = $request->query('status');
        $q = LeaveRequest::with('user');
        if ($status) {
            $q->where('status', $status);
        }
        return response()->json($q->get());
    }

    public function actOnLeave(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject,revision,cancel',
            'comment' => 'nullable|string'
        ]);
        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);
        $leave = LeaveRequest::findOrFail($id);
        $action = $request->action;
        if ($action === 'approve') {
            $leave->status = 'approved';
        } elseif ($action === 'reject') {
            $leave->status = 'rejected';
        } elseif ($action === 'revision') {
            $leave->status = 'revision';
        } elseif ($action === 'cancel') {
            $leave->status = 'cancelled';
        }
        $leave->comment = $request->comment;
        $leave->processed_by = JWTAuth::user()->id;
        $leave->processed_at = now();
        $leave->save();
        return response()->json(['message' => 'Action applied', 'leave' => $leave]);
    }
}
