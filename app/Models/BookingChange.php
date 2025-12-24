<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingChange extends Model
{
    protected $fillable = [
        'booking_id', 'changed_by', 'old_status', 'new_status', 'notes'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function changeReservation()
    {
        return $this->hasOne(ChangeReservation::class, 'change_id');
    }
}
