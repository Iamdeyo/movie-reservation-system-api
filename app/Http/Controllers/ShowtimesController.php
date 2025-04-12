<?php

namespace App\Http\Controllers;

use App\Models\Showtimes;
use App\Http\Requests\StoreShowtimesRequest;
use App\Http\Requests\UpdateShowtimesRequest;
use App\Http\Resources\SeatsCollection;
use App\Models\Reservations;
use App\Models\Seats;
use Illuminate\Http\JsonResponse;

class ShowtimesController extends Controller
{


    public function getSeats(string $showtimeId, string $selectedDate)
    {
        // 1. Get the showtime with its theater
        $showtime = Showtimes::with('theaters')->findOrFail($showtimeId);
        $theaterId = $showtime->theaters_id;

        // 2. Get all seats in the theater
        $allSeats = Seats::where('theaters_id', $theaterId)
            ->orderBy('row')
            ->orderBy('number')
            ->get();

        // 3. Get all reservations for this showtime & date
        $reservations = Reservations::where('showtimes_id', $showtimeId)
            ->whereDate('reservation_date', $selectedDate)
            ->with('seats') // Eager load seats
            ->get();

        // 4. Extract booked seat IDs from the reservation-seat relationship
        $bookedSeatIds = $reservations->flatMap(function ($reservation) {
            return $reservation->seats->pluck('id');
        })->unique()->values();

        return $this->ResponseJson(true, [
            'allSeats' => new SeatsCollection($allSeats),
            'bookedSeats' => $bookedSeatIds,
        ], 'Seat data fetched');
    }


    // public function getSeats(string $showtimeId, string $selectedDate): JsonResponse
    // {
    //     // Validate the date format first
    //     if (!strtotime($selectedDate)) {
    //         return $this->ResponseJson(false, null, 'Invalid date format', null, 400);
    //     }

    //     // 1. Get showtime with minimal data
    //     $showtime = Showtimes::select('id', 'theaters_id', 'start_time', 'end_time')
    //         ->with(['theaters:id,name'])
    //         ->find($showtimeId);

    //     if (!$showtime) {
    //         return $this->ResponseJson(false, null, 'Showtime not found', null, 404);
    //     }

    //     // 2. Get all seats with availability status in a single query
    //     $seats = Seats::where('theaters_id', $showtime->theaters_id)
    //         ->orderBy('row')
    //         ->orderBy('number')
    //         ->select(['id', 'row', 'number', 'type']) // Include seat type if available
    //         ->withCount(['reservations' => function ($query) use ($showtimeId, $selectedDate) {
    //             $query->whereHas('reservations', function ($q) use ($showtimeId, $selectedDate) {
    //                 $q->where('showtimes_id', $showtimeId)
    //                     ->whereDate('reservation_date', $selectedDate);
    //             });
    //         }])
    //         ->get()
    //         ->map(function ($seat) {
    //             return [
    //                 'id' => $seat->id,
    //                 'row' => $seat->row,
    //                 'number' => $seat->number,
    //                 'type' => $seat->type ?? 'standard',
    //                 'is_available' => $seat->reservations_count === 0
    //             ];
    //         });

    //     return $this->ResponseJson(
    //         true,
    //         [
    //             'theater' => $showtime->theaters,
    //             'showtime' => [
    //                 'id' => $showtime->id,
    //                 'start_time' => $showtime->start_time,
    //                 'end_time' => $showtime->end_time
    //             ],
    //             'seats' => $seats,
    //             'stats' => [
    //                 'total' => $seats->count(),
    //                 'available' => $seats->where('is_available', true)->count(),
    //                 'booked' => $seats->where('is_available', false)->count()
    //             ]
    //         ],
    //         'Seat availability retrieved',
    //         null,
    //         200
    //     );
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShowtimesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Showtimes $showtimes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Showtimes $showtimes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShowtimesRequest $request, Showtimes $showtimes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Showtimes $showtimes)
    {
        //
    }
}
