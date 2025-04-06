<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMoviesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1', // in minutes
            'poster' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'genre_ids' => 'sometimes|array', // for genre relationships
            'genre_ids.*' => 'integer|exists:genres,id' // validate each genre ID
        ];
    }
}
