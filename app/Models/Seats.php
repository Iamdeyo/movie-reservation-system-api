<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seats extends Model
{
    /** @use HasFactory<\Database\Factories\SeatsFactory> */
    use HasFactory;

    protected $fillable = [
        'row',
        'number'
    ];

    public function theaters()
    {
        return $this->belongsTo(Theaters::class);
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservations::class, 'reservation_seat');
    }
}
