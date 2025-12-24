<?php

namespace App\Http\Controllers;
;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
public function getUserApartments($id)
{
    $user = User::with('apartments')->find($id);

    if (!$user) {
        return response()->json(['status' => false, 'message' => 'User not found'], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'User apartments fetched successfully',
        'apartments' => $user->apartments
    ]);
}

public function getUserBookings($id)
{
    $user = User::with('bookings')->find($id);

    if (!$user) {
        return response()->json(['status' => false, 'message' => 'User not found'], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'User bookings fetched successfully',
        'bookings' => $user->bookings
    ]);
}

public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $oldRole = $user->role_id;
        $request->validate([
            'role_id' => 'required|int|min:1|max:3' . $user->role_id,
        ]);

        $user->update(['role_id' => $request->role_id,]);

        return response()->json([
            'message' => 'Role updated successfully',
            'oldRole' => $oldRole,
            'role' => $user
        ]);
    }
}
