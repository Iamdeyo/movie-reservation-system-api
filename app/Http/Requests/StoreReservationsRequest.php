<?php

namespace App\Http\Requests;

use App\Models\Seats;
use App\Models\Showtimes;
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
            'showtimes_id' => [
                'required',
                'exists:showtimes,id',
                function ($attribute, $value, $fail) {
                    $showtime = Showtimes::find($value);
                    $reservationDate = $this->reservation_date ?? now()->toDateString();

                    // Check if reservation date is after showtime's end_date (if it exists)
                    if ($showtime->end_date && $reservationDate > $showtime->end_date) {
                        $fail("Reservation date cannot be after the showtime's end date.");
                    }

                    // If reservation is for today, check if showtime start_time hasn't passed
                    if ($reservationDate === now()->toDateString()) {
                        $currentTime = now()->format('H:i:s');
                        if ($currentTime >= $showtime->start_time) {
                            $fail("Cannot make reservation for a showtime that has already started today.");
                        }
                    }
                }
            ],
            'theaters_id' => 'required|exists:theaters,id',
            'seats_ids' => [
                'required',
                'array',
                'min:1',
                // Validate seats exist in the same theater as the showtime
                function ($attribute, $value, $fail) {


                    $invalidSeats = Seats::whereIn('id', $value)
                        ->where('theaters_id', '!=', $this->theaters_id)
                        ->pluck('id')
                        ->toArray();

                    if (!empty($invalidSeats)) {
                        $fail("Some seats (" . implode(', ', $invalidSeats) . ") don't belong to this theater.");
                    }
                }
            ],
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
            'reservation_date' => [
                'sometimes',
                'date',
                'after_or_equal:today',
            ]
        ];
    }

    public function messages()
    {
        return [
            'seats_ids.*.unique' => 'One or more seats are already booked for this showtime'
        ];
    }
    protected function prepareForValidation(): void
    {
        $data = [];

        $data['reservation_date'] = $this->reservation_date  ?? now()->toDateString();

        $this->merge($data);
    }
}
