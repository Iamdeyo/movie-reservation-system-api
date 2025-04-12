<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservations extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationsFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'showtimes_id',
        'reservation_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showtimes()
    {
        return $this->belongsTo(Showtimes::class);
    }
    public function seats()
    {
        return $this->belongsToMany(Seats::class, 'reservation_seat');
    }
}
