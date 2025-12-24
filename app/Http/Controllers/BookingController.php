<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeBookingRequest;
use App\Models\Apartment;
use App\Models\Booking;
use App\Models\BookingChange;
use App\Models\ChangeReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['client',
                                    'changes',
                                    'changeReservation.apartment'
        ])->orderBy('id', 'desc')->get();

        return response()->json($bookings);
    }

    public function createBooking(storeBookingRequest $req)
    {
        $d=$req->validated();

        $apartment = Apartment::find($req->apartment_id);

        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found'], 404);
        }
        $from = $req->start_date;
        $to   = $req->end_date;

        return DB::transaction(function () use ($apartment, $from, $to) {
            $existing = Booking::whereHas('changeReservation', function($q) use ($apartment) {
                $q->where('apartment_id', $apartment->id);
            })
                ->where(function ($q) use ($from, $to) {
                    $q->where('start_date', '<', $to)
                        ->where('end_date', '>', $from);
                })
                ->lockForUpdate()
                ->exists();

            if ($existing) {
                return response()->json([
                    'status' => false,
                    'message' => 'This apartment is already booked for the selected dates.'
                ], 409);
            }

            $days  = (new \Carbon\Carbon($from))->diffInDays(new \Carbon\Carbon($to));
            $total = $days * $apartment->price_per_day;
            $data=[
                'client_id'   => auth()->id(),
                'start_date'  => $from,
                'end_date'    => $to,
                'total_price' => $total,
                'status'      => 'pending',
                'owner_approval' => 'pending',// pending / approved / rejected
                ];

            $booking = Booking::create($data);

            ChangeReservation::create([
                'change_id'    => null,
                'booking_id'   => $booking->id,
                'owner_id'     => $apartment->owner_id,
                'apartment_id' => $apartment->id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Booking created successfully',
                'booking' => $booking
            ]);
        });
    }

    public function updateStatus(Request $req, $id)
    {
        $req->validate([
            'status' => 'required|string|in:pending,confirmed,cancelled',// pending, confirmed, cancelled
        ],[
            'status.in' => 'Status must be pending or confirmed  or  cancelled.',
        ]);

        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $change = BookingChange::create([
            'booking_id' => $booking->id,
            'changed_by' => auth()->user()->role_id == 1 ? 'owner' : 'admin',
            'old_status' => $booking->status,
            'new_status' => $req->status,
            'notes'      => $req->notes ?? null
        ]);
        $booking->status = $req->status;
        $booking->save();

        ChangeReservation::where('booking_id', $booking->id)
            ->update(['change_id' => $change->id]);

        return response()->json([
            'message' => 'Status updated successfully',
            'booking' => $booking
        ]);
    }

    public function bookingDetails($id)
    {
        $booking = Booking::with([
            'client',
            'changes',
            'changeReservation.apartment'
        ])->find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return response()->json($booking);
    }

    public function indexForUser(Request $req)
    {
        $user = auth()->user();
        $query = Booking::with('changeReservation.apartment')
            ->where('client_id', $user->id);

        if ($req->city) {
            $query->whereHas('changeReservation.apartment', function ($q) use ($req) {
                $q->where('city', 'like', '%' . $req->city . '%');
            });
        }

        if ($req->status) {
            $query->where('status', $req->status);
        }

        return response()->json(['bookings' => $query->get()]);
    }

    public function cancel($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($booking->client_id !=auth()->id()) {
            return response()->json(['message' => 'The user is not the owner of the booking.'], 403);
        }

        return $this->updateStatus(new Request(['status' => 'cancelled']), $id);
    }
}
