<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Booking;
use App\Models\ChangeReservation;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index()
    {
        $book=auth()->user()->bookings()->get();

        if ($book->isEmpty()) {
            return response()->json(["message" => "No book found"], 404);
        }

        return response()->json([
            'status' => true,
            'count' => $book->count(),
            'users' => $book
        ], 200);
    }

    public function pendingUsers()
    {
        $book=auth()->user()->bookings()->where('owner_approval','pending')->get();
        return response()->json([
            'status' => true,
            'count'  => $book->count(),
            'users'  => $book
        ]);
    }

    public function approvedUsers()
    {
        $book=auth()->user()->bookings()->where('owner_approval','approved')->get();
        return response()->json([
            'status' => true,
            'count'  => $book->count(),
            'users'  => $book
        ]);
    }

    public function rejectedUsers()
    {
        $book=auth()->user()->bookings()->where('owner_approval','rejected')->get();
        return response()->json([
            'status' => true,
            'count'  => $book->count(),
            'users'  => $book
        ]);
    }

    public function approveUser($id)
    {
        $book=auth()->user()->bookings()->find($id);
        if (!$book) {
            return response()->json([
                'status' => false,
                'message' => '$book not found'
            ], 404);
        }

        $book->owner_approval = 'approved';
        $book->save();

        return response()->json([
            'status' => true,
            'message' => 'owner approved successfully'
        ], 200);
    }

    public function rejectUser($id)
    {
        $book=auth()->user()->bookings()->find($id);

        if (!$book) {
            return response()->json([
                'status' => false,
                'message' => '$book not found'
            ], 404);
        }

        $book->owner_approval = 'rejected';
        $book->save();

        return response()->json([
            'status' => true,
            'message' => 'owner rejected successfully'
        ], 200);
    }

    function deleteUser($id)
    {
        $book=auth()->user()->bookings()->find($id);

        if (!$book) {
            return response()->json([
                'status' => false,
                'message' => '$book not found'
            ], 404);
        }

        $book->delete();

        return response()->json([
            'status' => true,
            'message' => 'owner deleted successfully'
        ], 200);
    }
}
