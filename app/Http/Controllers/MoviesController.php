<?php

namespace App\Http\Controllers;

use App\Models\Movies;
use App\Http\Requests\StoreMoviesRequest;
use App\Http\Requests\UpdateMoviesRequest;
use App\Http\Resources\MoviesCollection;
use App\Http\Resources\MoviesResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Movies::query();

        // 🎯 Filter by genre_id (many-to-many)
        if ($genreId = $request->query('genreId')) {
            $query->whereHas('genres', function ($q) use ($genreId) {
                $q->where('genres.id', $genreId);
            });
        }

        // 🔍 Search by title
        if ($search = $request->query('search')) {
            $query->where('title', 'like', "%$search%");
        }

        // 📅 Get selected date or use today as default
        $selectedDate = $request->query('selectedDate', now()->toDateString());

        // ❗ Only include movies with at least one valid showtime on/after selected date
        $query->whereHas('showtimes', function ($q) use ($selectedDate) {
            $q->where(function ($query) use ($selectedDate) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $selectedDate);
            });
        });

        // 🔽 Sorting
        $sortOrder = $request->query('order', 'desc');
        $sortBy = $request->query('sortBy', 'date');

        if ($sortBy === 'name') {
            $query->orderBy('title', $sortOrder);
        } else {
            $query->orderBy('created_at', $sortOrder);
        }

        // 📄 Pagination with eager loading of valid showtimes
        $movies = $query->with([
            'genres',
            'showtimes' => function ($q) use ($selectedDate) {
                $q->where(function ($query) use ($selectedDate) {
                    $query->whereNull('end_date')
                        ->orWhereDate('end_date', '>=', $selectedDate);
                });

                // ⏱️ If selected date is today, exclude showtimes that already started
                if ($selectedDate === now()->toDateString()) {
                    $q->where('start_time', '>=', now()->format('H:i:s'));
                }
            },
        ])->paginate(10)->appends($request->query());

        return $this->ResponseJson(
            true,
            new MoviesCollection($movies),
            'Movies found',
            [
                'current_page' => $movies->currentPage(),
                'total' => $movies->total(),
                'per_page' => $movies->perPage(),
                'last_page' => $movies->lastPage(),
            ],
            200
        );
    }

    public function all(Request $request): JsonResponse
    {
        $query = Movies::query();

        // 🎯 Filter by genre_id (many-to-many)
        if ($genreId = $request->query('genreId')) {
            $query->whereHas('genres', function ($q) use ($genreId) {
                $q->where('genres.id', $genreId);
            });
        }

        // 🔍 Search by title
        if ($search = $request->query('search')) {
            $query->where('title', 'like', "%$search%");
        }

        // 🔽 Sorting
        $sortOrder = $request->query('order', 'desc');
        $sortBy = $request->query('sortBy', 'date');

        if ($sortBy === 'name') {
            $query->orderBy('title', $sortOrder);
        } else {
            $query->orderBy('created_at', $sortOrder);
        }

        // 📄 Pagination
        $movies = $query->with(['genres', 'showtimes'])->paginate(10)->appends($request->query());

        return $this->ResponseJson(
            true,
            new MoviesCollection($movies),
            'Movies found',
            [
                'current_page' => $movies->currentPage(),
                'total' => $movies->total(),
                'per_page' => $movies->perPage(),
                'last_page' => $movies->lastPage(),
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMoviesRequest $request)
    {
        DB::beginTransaction();

        try {
            // Generate filename
            $posterName = 'poster_' . now()->format('Ymd_His') . '_' . Str::random(8) . '.' . $request->file('poster')->extension();

            // Store file
            $posterPath = $request->file('poster')->storeAs(
                'posters',
                $posterName,
                'public'
            );

            // 2. Create movie
            $movie = Movies::create([
                'title' => $request->title,
                'description' => $request->description,
                'duration' => $request->duration,
                'poster' => $posterPath,
            ]);

            // 3. Sync genres (if provided)
            if ($request->has('genre_ids')) {
                $movie->genres()->sync($request->genre_ids);
            }

            DB::commit();

            return $this->ResponseJson(
                true,
                new MoviesResource($movie),
                'Movie created successfully',
                null,
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();

            // Delete the uploaded file if transaction failed
            if (isset($posterPath)) {
                Storage::disk('public')->delete($posterPath);
            }

            return $this->ResponseJson(
                false,
                null,
                'Failed to create movie: ' . $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        // $movie = Movies::with([
        //     'genres',
        //     'showtimes.theaters'
        // ])->find($id);
        $selectedDate = $request->query('selectedDate', now()->toDateString());
        $nowTime = now()->format('H:i:s');

        $movie = Movies::where('id', $id)
            ->whereHas('showtimes', function ($query) use ($selectedDate) {
                $query->where(function ($q) use ($selectedDate) {
                    $q->whereNull('end_date')
                        ->orWhereDate('end_date', '>=', $selectedDate);
                });
            })
            ->with([
                'genres',
                'showtimes' => function ($query) use ($selectedDate, $nowTime) {
                    $query->select('id', 'movies_id', 'theaters_id', 'start_time', 'end_time', 'price', 'end_date')
                        ->where(function ($q) use ($selectedDate) {
                            $q->whereNull('end_date')
                                ->orWhereDate('end_date', '>=', $selectedDate);
                        });

                    if ($selectedDate === now()->toDateString()) {
                        $query->where('start_time', '>=', $nowTime);
                    }

                    $query->with('theaters');
                }
            ])
            ->first();

        if (!$movie) {
            return $this->ResponseJson(false, null, "Movie not found.", null, 404);
        }

        return $this->ResponseJson(true, new MoviesResource($movie), "Movie found", null, 200);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMoviesRequest $request, string $id)
    {
        DB::beginTransaction();

        try {
            $movie = Movies::find($id);

            if (!$movie) {
                return $this->ResponseJson(
                    false,
                    null,
                    'Movie not found',
                    null,
                    404
                );
            }

            // Handle file upload if new poster provided
            if ($request->hasFile('poster')) {
                // Delete old poster if exists
                if ($movie->poster && Storage::disk('public')->exists($movie->poster)) {
                    Storage::disk('public')->delete($movie->poster);
                }

                // Store new poster
                $posterName = 'poster_' . now()->format('Ymd_His') . '_' . Str::random(8) . '.' . $request->file('poster')->extension();
                $posterPath = $request->file('poster')->storeAs(
                    'posters',
                    $posterName,
                    'public'
                );
                $movie->poster = $posterPath;
            }

            // Update other fields
            $movie->fill($request->only(['title', 'description', 'duration']));

            // Update genres if provided
            if ($request->has('genre_ids')) {
                $movie->genres()->sync($request->genre_ids);
            }

            $movie->save();
            DB::commit();

            return $this->ResponseJson(
                true,
                new MoviesResource($movie),
                'Movie updated successfully',
                null,
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->ResponseJson(
                false,
                null,
                'Failed to update movie: ' . $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            // Find the movie with its relationships
            $movie = Movies::with('genres', 'showtimes')->find($id);

            if (!$movie) {
                return $this->ResponseJson(
                    false,
                    null,
                    'Movie not found',
                    null,
                    404
                );
            }

            // Store the poster path before deletion
            $posterPath = $movie->poster;

            // Delete associated records
            $movie->genres()->detach(); // Remove all genre relationships
            $movie->showtimes()->delete(); // Delete all showtimes

            // Delete the movie
            $movie->delete();

            // Delete the poster file if it exists
            if ($posterPath && Storage::disk('public')->exists($posterPath)) {
                Storage::disk('public')->delete($posterPath);
            }

            DB::commit();

            return $this->ResponseJson(
                true,
                null,
                'Movie deleted successfully',
                null,
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->ResponseJson(
                false,
                null,
                'Failed to delete movie: ' . $e->getMessage(),
                null,
                500
            );
        }
    }
}
