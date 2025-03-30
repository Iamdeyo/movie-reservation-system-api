<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtimes extends Model
{
    /** @use HasFactory<\Database\Factories\ShowtimesFactory> */
    use HasFactory;

    public function movie()
    {
        return $this->belongsTo(Movies::class);
    }

    public function theater()
    {
        return $this->belongsTo(Theaters::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservations::class);
    }
}
