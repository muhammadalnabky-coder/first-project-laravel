<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_Setting extends Model
{
    protected $guarded = [];
    public function user_setting(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
