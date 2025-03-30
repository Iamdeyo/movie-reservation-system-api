<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seats extends Model
{
    /** @use HasFactory<\Database\Factories\SeatsFactory> */
    use HasFactory;

    public function theater()
    {
        return $this->belongsTo(Theaters::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservations::class);
    }
}
