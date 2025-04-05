<?php

namespace App\Http\Controllers;

use App\Models\Theaters;
use App\Http\Requests\StoreTheatersRequest;
use App\Http\Requests\UpdateTheatersRequest;
use App\Http\Resources\TheatersCollection;
use App\Http\Resources\TheatersResource;
use App\Models\Seats;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TheatersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Theaters::query();

        // 🔍 Search by name only
        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%$search%");
        }

        // 🔽 Sorting
        $sortBy = $request->query('sortBy', 'date'); // default to created_at
        $sortOrder = $request->query('order', 'desc'); // default to most recent first

        if ($sortBy === 'name') {
            $query->orderBy('name', $sortOrder);
        } else {
            $query->orderBy('created_at', $sortOrder);
        }

        // 📄 Pagination
        $theaters = $query->with('seats')->paginate(10)->appends($request->query());

        return $this->ResponseJson(
            true,
            new TheatersCollection($theaters),
            'Theaters data retrieved successfully',
            [
                'current_page' => $theaters->currentPage(),
                'total' => $theaters->total(),
                'per_page' => $theaters->perPage(),
                'last_page' => $theaters->lastPage(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTheatersRequest $request)
    {
        DB::beginTransaction();

        try {
            $theater = Theaters::create([
                'name' => $request->name,
            ]);

            $seats = collect($request->seats)->map(function ($seat) {
                return new Seats([
                    'row' => $seat['row'],
                    'number' => $seat['number'],
                ]);
            });

            $theater->seats()->saveMany($seats);

            DB::commit();

            return $this->ResponseJson(true, new TheatersResource($theater->load('seats')), 'Theater created with seats', null, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->ResponseJson(false, null, 'Failed to create theater', 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $theater = Theaters::find($id);
        if (!$theater) {
            return $this->ResponseJson(false, null, 'No Theater Found', null, 404);
        }
        return $this->ResponseJson(true, new TheatersResource($theater->load('seats')), 'Theater Found');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTheatersRequest $request, string $id)
    {
        $theater = Theaters::find($id);
        if (!$theater) {
            return $this->ResponseJson(false, null, 'No Theater Found', null, 404);
        }

        $theater->update($request->all());

        return $this->ResponseJson(true, new TheatersResource($theater), 'Theater Updated', null);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $theater = Theaters::find($id);
        if (!$theater) {
            return $this->ResponseJson(false, null, 'No Theater Found', null, 404);
        }

        $theater->delete();

        return $this->ResponseJson(true, null, 'Theater Deleted');
    }
}
