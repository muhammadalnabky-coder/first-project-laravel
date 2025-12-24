<?php

namespace App\Http\Controllers;

use App\Models\ChangeReservation;

class ChangeReservationController extends Controller
{
    public function storeLink($changeId, $bookingId, $ownerId, $apartmentId)
    {
        return ChangeReservation::create([
            'change_id'    => $changeId,
            'booking_id'   => $bookingId,
            'owner_id'     => $ownerId,
            'apartment_id' => $apartmentId,
        ]);
    }
}
