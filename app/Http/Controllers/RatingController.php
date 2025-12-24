<?php

namespace App\Http\Controllers;

use App\Http\Requests\ratingRequest;
use App\Models\Apartment;
use App\Models\Booking;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function rate(ratingRequest $req)
    {
        $data=$req->validated();

        $booking = Booking::find($req->booking_id);

        if ($booking->client_id != auth()->id()) {
           return response()->json(['error' => 'You are not allowed to rate this apartment'], 403);
        }

        if (now()->lt($booking->end_date)) {
            return response()->json(['message' => 'You can rate only after checkout'], 400);
        }

        $apartment = $booking->changeReservation->apartment;

        if (Rating::where('booking_id', $booking->id)->exists()) {
            return response()->json(['message' => 'You already rated this booking'], 400);
        }

        $rating = Rating::create([
            'booking_id'   => $booking->id,
            'client_id'    => auth()->id(),
            'apartment_id' => $apartment->id,
            'rating'       => $req->rating,
            'comment'       => $req->comment,
        ]);

        return response()->json([
            'message' => 'Rating submitted successfully',
            'rating'  => $rating
        ]);
    }

    public function listByApartment($apartment_id)
    {
        $ratings = Rating::with('client')
            ->where('apartment_id', $apartment_id)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'ratings' => $ratings,
            'average' => $ratings->avg('rating')
        ]);
    }
}
