<?php

namespace App\Http\Controllers;

use App\Http\Requests\updateBookingRequest;
use App\Models\Apartment;
use App\Models\Booking;
use App\Models\BookingChange;
use App\Models\ChangeReservation;
use Illuminate\Support\Facades\Auth;

class BookingChangeController extends Controller
{
    public function updateBooking(updateBookingRequest $req, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($booking->client_id !==auth()->id()) {
            return response()->json(['message' => 'The user is not the owner of the booking.'], 403);
        }

        $oldData = [
            'apartment_id' => $booking->changeReservation->apartment_id,
            'start_date'   => $booking->start_date,
            'end_date'     => $booking->end_date,
            'total_price'  => $booking->total_price
        ];

        if ($req->start_date) $booking->start_date = $req->start_date;
        if ($req->end_date)   $booking->end_date   = $req->end_date;

        if ($req->apartment_id) {
            $booking->changeReservation->update(['apartment_id' => $req->apartment_id]);
        }

        if ($booking->start_date && $booking->end_date) {

            $apartment = Apartment::find($booking->changeReservation->apartment_id);

            $days = now()
                ->parse($booking->start_date)
                ->diffInDays(now()->parse($booking->end_date));
            $booking->total_price = $days * $apartment->price_per_day;
        }

        $booking->save();

        $book=BookingChange::create([
            'booking_id' => $booking->id,
            'changed_by' => auth()->user()->role_id == 1 ? 'owner' : 'admin',
            'old_status' => json_encode($oldData),
            'new_status' => json_encode([
                'apartment_id' => $booking->changeReservation->apartment_id,
                'start_date'   => $booking->start_date,
                'end_date'     => $booking->end_date,
                'total_price'  => $booking->total_price
            ]),
            'notes'      => $req->notes ?? 'Booking updated.'
        ]);

        ChangeReservation::create([
            'change_id'    =>$book->id ,
            'booking_id'   => $booking->id,
            'owner_id'     => $apartment->owner_id,
            'apartment_id' => $apartment->id,
        ]);

        $bookings = BookingChange::with([
            'booking',
            'changeReservation'
        ])->get();

        return response()->json([

            'message' => 'Booking updated successfully',
            'booking' => $bookings
        ]);
    }
}
