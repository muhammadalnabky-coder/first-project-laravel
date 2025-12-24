<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{

    public function index()
    {
        $users=User::with(['role',
            'favorites',
            'sentMessages',
            'receivedMessages',
            'apartments',
            'bookings',
        ])->get();

        return response()->json([
            'status' => true,
            'count' => $users->count(),
            'users' => $users
        ], 200);
        }

    public function pendingUsers()
    {
        $users = User::where('status', 'pending')->get();

        return response()->json([
            'status' => true,
            'count'  => $users->count(),
            'users'  => $users
        ]);
    }

    public function approvedUsers()
    {
        $users = User::where('status', 'approved')->get();

        return response()->json([
            'status' => true,
            'count' => $users->count(),
            'users' => $users
        ], 200);
    }

    public function rejectedUsers()
    {
        $users = User::where('status', 'rejected')->get();

        return response()->json([
            'status' => true,
            'count' => $users->count(),
            'users' => $users
        ], 200);
    }

    public function approveUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->status = 'approved';
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User approved successfully'
        ], 200);
    }

    public function rejectUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->status = 'rejected';
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User rejected successfully'
        ], 200);
    }

     function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ], 200);
    }
}

