<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMoviesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'duration' => 'sometimes|integer|min:1',
            'poster' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genre_ids' => 'sometimes|array',
            'genre_ids.*' => 'integer|exists:genres,id'
        ];
    }
}
