<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theaters extends Model
{
    /** @use HasFactory<\Database\Factories\TheatersFactory> */
    use HasFactory;
    public function seats()
    {
        return $this->hasMany(Seats::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtimes::class);
    }
}
