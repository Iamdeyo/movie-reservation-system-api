<?php

namespace App\Http\Controllers;

use App\Models\Reservations;
use App\Http\Requests\StoreReservationsRequest;
use App\Http\Requests\UpdateReservationsRequest;
use App\Http\Resources\ReservationsCollection;
use App\Http\Resources\ReservationsResource;
use Illuminate\Support\Facades\DB;

class ReservationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user();

        $query = Reservations::with([
            'user',
            'showtimes.movies',
            'showtimes.theaters',
            'seats',
        ]);

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $reservations = $query->latest()->paginate(10);

        return $this->ResponseJson(
            true,
            new ReservationsCollection($reservations),
            'Reservations found',
            [
                'current_page' => $reservations->currentPage(),
                'total' => $reservations->total(),
                'per_page' => $reservations->perPage(),
                'last_page' => $reservations->lastPage(),
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationsRequest $request)
    {
        DB::beginTransaction();

        try {
            // Create the reservation
            $reservation = Reservations::create([
                'user_id' => $request->user()->id,
                'showtimes_id' => $request->showtimes_id,
                'reservation_date' => $request->reservation_date ?? now()->toDateString()
            ]);

            // Attach seats through the pivot table
            $reservation->seats()->attach($request->seats_ids);

            DB::commit();

            return $this->ResponseJson(
                true,
                new ReservationsResource($reservation),
                'Reservation created successfully',
                null,
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->ResponseJson(
                false,
                null,
                'Failed to create reservation: ' . $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = request()->user();

        $reservation = Reservations::with([
            'user',
            'showtimes.movies',
            'showtimes.theaters',
            'seats',
        ])->find($id);

        if ($user->role !== 'admin') {
            $reservation->where('user_id', $user->id);
        }

        return $this->ResponseJson(
            true,
            new ReservationsResource($reservation),
            'Reservation found',
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservations $reservations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationsRequest $request, Reservations $reservations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservations $reservations)
    {
        //
    }
}
