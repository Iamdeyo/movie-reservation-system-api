<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movies extends Model
{
    /** @use HasFactory<\Database\Factories\MoviesFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration',
        'poster'
    ];

    public function genres()
    {
        return $this->belongsToMany(Genres::class, 'genre_movie');
    }

    public function showtimes()
    {
        return $this->hasMany(Showtimes::class);
    }
}
