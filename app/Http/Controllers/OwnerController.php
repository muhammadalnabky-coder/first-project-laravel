<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Booking;
use App\Models\ChangeReservation;
use App\Models\User;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index()
    {
        $book =auth()->user()->apartmentBookings()

        ->with([
            'client:id,first_name,last_name',
            'changeReservation.apartment:id,title'
        ])
        ->get()
        ->map(function ($b) {
            return [
                'booking_id' => $b->booking_id,
                'apartment'  => [
                    'id'    => optional($b->changeReservation?->apartment)->id,
                    'title' => optional($b->changeReservation?->apartment)->title,
                ],
                'client' => [
                    'id'         => $b->client->id,
                    'first_name' => $b->client->first_name,
                    'last_name'  => $b->client->last_name,
                ],
                'start_date'  => $b->start_date,
                'end_date'    => $b->end_date,
                'total_price' => $b->total_price,
                'status'      => $b->status,
                'owner_approval' => $b->owner_approval,
            ];
        });


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
        $book = Booking::where('owner_approval','pending')
            ->whereHas('changeReservation.apartment', function ($q) {
                $q->where('owner_id', auth()->id());
            })
            ->with([
                'client:id,first_name,last_name',
                'changeReservation.apartment:id,title'
            ])
            ->select([
                'bookings.id as booking_id',
                'bookings.client_id',
                'bookings.start_date',
                'bookings.end_date',
                'bookings.total_price',
                'bookings.status',
                'bookings.owner_approval'
            ])
            ->get()
            ->map(function ($b) {
                return [
                    'booking_id' => $b->booking_id,
                    'apartment'  => [
                        'id'    => optional($b->changeReservation?->apartment)->id,
                        'title' => optional($b->changeReservation?->apartment)->title,
                    ],
                    'client' => [
                        'id'         => $b->client->id,
                        'first_name' => $b->client->first_name,
                        'last_name'  => $b->client->last_name,
                    ],
                    'start_date'  => $b->start_date,
                    'end_date'    => $b->end_date,
                    'total_price' => $b->total_price,
                    'status'      => $b->status,
                    'owner_approval' => $b->owner_approval,
                ];
            });



        return response()->json([
            'status' => true,
            'count'  => $book->count(),
            'users'  => $book
        ]);
    }

    public function approvedUsers()
    {
        $book = Booking::where('owner_approval','approved')
            ->whereHas('changeReservation.apartment', function ($q) {
                $q->where('owner_id', auth()->id());
            })
            ->with([
                'client:id,first_name,last_name',
                'changeReservation.apartment:id,title'
            ])
            ->select([
                'bookings.id as booking_id',
                'bookings.client_id',
                'bookings.start_date',
                'bookings.end_date',
                'bookings.total_price',
                'bookings.status',
                'bookings.owner_approval'
            ])
            ->get()
            ->map(function ($b) {
                return [
                    'booking_id' => $b->booking_id,
                    'apartment'  => [
                        'id'    => optional($b->changeReservation?->apartment)->id,
                        'title' => optional($b->changeReservation?->apartment)->title,
                    ],
                    'client' => [
                        'id'         => $b->client->id,
                        'first_name' => $b->client->first_name,
                        'last_name'  => $b->client->last_name,
                    ],
                    'start_date'  => $b->start_date,
                    'end_date'    => $b->end_date,
                    'total_price' => $b->total_price,
                    'status'      => $b->status,
                    'owner_approval' => $b->owner_approval,
                ];
            });


        return response()->json([
            'status' => true,
            'count'  => $book->count(),
            'users'  => $book
        ]);
    }

    public function rejectedUsers()
    {
        $book = Booking::where('owner_approval','rejected')
            ->whereHas('changeReservation.apartment', function ($q) {
                $q->where('owner_id', auth()->id());
            })
            ->with([
                'client:id,first_name,last_name',
                'changeReservation.apartment:id,title'
            ])
            ->select([
                'bookings.id as booking_id',
                'bookings.client_id',
                'bookings.start_date',
                'bookings.end_date',
                'bookings.total_price',
                'bookings.status',
                'bookings.owner_approval'
            ])
            ->get()
            ->map(function ($b) {
                return [
                    'booking_id' => $b->booking_id,
                    'apartment'  => [
                        'id'    => optional($b->changeReservation?->apartment)->id,
                        'title' => optional($b->changeReservation?->apartment)->title,
                    ],
                    'client' => [
                        'id'         => $b->client->id,
                        'first_name' => $b->client->first_name,
                        'last_name'  => $b->client->last_name,
                    ],
                    'start_date'  => $b->start_date,
                    'end_date'    => $b->end_date,
                    'total_price' => $b->total_price,
                    'status'      => $b->status,
                    'owner_approval' => $b->owner_approval,
                ];
            });


        return response()->json([
            'status' => true,
            'count'  => $book->count(),
            'users'  => $book
        ]);
    }

    public function approveUser($id)
    {
        $book = auth()->user()
            ->apartmentBookings()
            ->where('bookings.id', $id)
            ->with(['client','changeReservation.apartment'])
            ->first();

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
        $book = auth()->user()
            ->apartmentBookings()
            ->where('bookings.id', $id)
            ->with(['client','changeReservation.apartment'])
            ->first();

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
        $book = auth()->user()
            ->apartmentBookings()
            ->where('bookings.id', $id)
            ->with(['client','changeReservation.apartment'])
            ->first();

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
