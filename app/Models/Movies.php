<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movies extends Model
{
    /** @use HasFactory<\Database\Factories\MoviesFactory> */
    use HasFactory;

    public function genres()
    {
        return $this->belongsToMany(Genres::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtimes::class);
    }
}
