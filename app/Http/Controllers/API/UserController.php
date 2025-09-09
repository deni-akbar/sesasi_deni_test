<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    // apply middleware auth:api or jwt auth
    public function createLeave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'details' => 'nullable|array'
        ]);
        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);
        $leave = LeaveRequest::create([
            'user_id' => JWTAuth::user()->id,
            'type' => $request->type,
            'title' => $request->title,
            'content' => $request->content,
            'details' => $request->details,
            'status' => 'submitted',
            'submitted_at' => now()
        ]);
        return response()->json(['message' => 'Leave submitted', 'leave' => $leave], 201);
    }

    public function listLeaves()
    {
        $leaves = LeaveRequest::where('user_id', JWTAuth::user()->id)->get();
        return response()->json($leaves);
    }

    public function showLeave($id)
    {
        $leave = LeaveRequest::where('user_id', JWTAuth::user()->id)->find($id);
        if (!$leave) {
            return response()->json(['error' => 'Leave not found'], 404);
        }
        return response()->json($leave);
    }

    public function updateLeave(Request $request, $id)
    {
        $leave = LeaveRequest::where('user_id', JWTAuth::user()->id)->find($id);
        if (!$leave) {
            return response()->json(['error' => 'Leave not found'], 404);
        }
        if (!in_array($leave->status, ['draft', 'submitted', 'revision'])) {
            return response()->json(['error' => 'Cannot edit leave in its current status'], 403);
        }
        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|string',
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'details' => 'nullable|array'
        ]);
        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);
        $leave->fill($request->only(['type', 'title', 'content', 'details']));
        $leave->save();
        return response()->json(['message' => 'Leaveupdated', 'leave' => $leave]);
    }

    public function cancelLeave($id)
    {
        $leave = LeaveRequest::where('user_id', JWTAuth::user()->id)->find($id);
        if (!$leave) {
            return response()->json(['error' => 'Leave not found'], 404);
        }
        if (in_array($leave->status, ['approved', 'rejected', 'cancelled'])) {
            return response()->json(['error' => 'Cannot cancel leave in its current status'], 403);
        }
        $leave->status = 'cancelled';
        $leave->processed_at = now();
        $leave->save();
        return response()->json(['message' => 'Leave cancelled', 'leave' => $leave]);
    }

    public function deleteLeave($id)
    {
        $leave = LeaveRequest::where('user_id', JWTAuth::user()->id)->find($id);
        if (!$leave) {
            return response()->json(['error' => 'Leave not found'], 404);
        }
        if (!in_array(
            $leave->status,
            ['draft', 'submitted', 'revision', 'rejected']
        )) {
            return response()->json(['error' => 'Cannot delete leave in its current status'], 403);
        }
        $leave->delete();
        return response()->json(['message' => 'Leave deleted']);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);
        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);
        $user = JWTAuth::user();
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return response()->json(
                ['error' => 'Current password incorrect'],
                403
            );
        }
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();
        return response()->json(['message' => 'Password updated']);
    }
}
