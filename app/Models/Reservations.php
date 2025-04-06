<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservations extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationsFactory> */
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function showtimes()
    {
        return $this->belongsTo(Showtimes::class);
    }

    public function seats()
    {
        return $this->belongsTo(Seats::class);
    }
}
