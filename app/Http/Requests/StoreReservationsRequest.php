<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReservationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Changed to true to allow authorized requests
    }

    public function rules(): array
    {
        return [
            'showtimes_id' => 'required|exists:showtimes,id',
            'seats_ids' => 'required|array|min:1',
            'seats_ids.*' => [
                'required',
                'exists:seats,id',
                Rule::unique('reservation_seat', 'seats_id')
                    ->where(function ($query) {
                        return $query->whereIn('reservations_id', function ($q) {
                            $q->select('id')
                                ->from('reservations')
                                ->where('showtimes_id', $this->showtimes_id)
                                ->whereDate('reservation_date', $this->reservation_date);
                        });
                    })
            ],
            'reservation_date' => 'sometimes|date|after_or_equal:today'
        ];
    }

    public function messages()
    {
        return [
            'seats_ids.*.unique' => 'One or more seats are already booked for this showtime'
        ];
    }
}
