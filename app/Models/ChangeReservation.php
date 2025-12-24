<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeReservation extends Model
{
    protected $fillable = [
        'change_id', 'booking_id', 'owner_id', 'apartment_id'
    ];

    public function change()
    {
        return $this->belongsTo(BookingChange::class, 'change_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class, 'apartment_id');
    }
}
