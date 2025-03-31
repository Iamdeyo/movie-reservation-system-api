<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservations extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationsFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showtime()
    {
        return $this->belongsTo(Showtimes::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seats::class);
    }
}
