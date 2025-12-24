<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'client_id', 'start_date', 'end_date', 'total_price', 'status','owner_approval'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function changes()
    {
        return $this->hasMany(BookingChange::class);
    }

    public function changeReservation()
    {
        return $this->hasOne(ChangeReservation::class, 'booking_id');
    }
}
