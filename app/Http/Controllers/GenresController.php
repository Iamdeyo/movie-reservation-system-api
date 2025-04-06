<?php

namespace App\Http\Controllers;

use App\Models\Genres;
use App\Http\Requests\StoreGenresRequest;
use App\Http\Requests\UpdateGenresRequest;
use App\Http\Resources\GenresCollection;
use App\Http\Resources\GenresResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class GenresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $genres = Genres::all();
        return $this->ResponseJson(true, new GenresCollection($genres), 'All Generes', null);
    }
    /**
     * Store a newly created genre in storage.
     */
    public function store(StoreGenresRequest $request)
    {
        try {
            $genre = Genres::create($request->validated());
            return $this->ResponseJson(
                true,
                new GenresResource($genre),
                'Genre created successfully',
                null,
                201
            );
        } catch (\Exception $e) {
            return $this->ResponseJson(
                false,
                null,
                'Failed to create genre: ' . $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Update the specified genre in storage.
     */
    public function update(UpdateGenresRequest $request, string $id)
    {


        try {
            $genre = Genres::find($id);

            if (!$genre) {
                return $this->ResponseJson(
                    false,
                    null,
                    'Genre not found',
                    null,
                    404
                );
            }

            $genre->update($request->validated());

            return $this->ResponseJson(
                true,
                new GenresResource($genre),
                'Genre updated successfully',
                null,
                200
            );
        } catch (\Exception $e) {

            return $this->ResponseJson(
                false,
                null,
                'Failed to update genre: ' . $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Remove the specified genre from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $genre = Genres::find($id);

            if (!$genre) {
                return $this->ResponseJson(
                    false,
                    null,
                    'Genre not found',
                    null,
                    404
                );
            }

            // Check if genre is being used by any movies
            if ($genre->movies()->exists()) {
                return $this->ResponseJson(
                    false,
                    null,
                    'Cannot delete genre because it is associated with movies',
                    null,
                    409
                );
            }

            $genre->delete();

            DB::commit();

            return $this->ResponseJson(
                true,
                null,
                'Genre deleted successfully',
                null,
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->ResponseJson(
                false,
                null,
                'Failed to delete genre: ' . $e->getMessage(),
                null,
                500
            );
        }
    }
}
