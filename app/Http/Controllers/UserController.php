<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // 🎭 Filter by role (only users, excluding admins)
        $query->where('role', 'user');

        // 🔍 Search by name or email
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        // 🔽 Sorting
        $sortBy = $request->query('sortBy', 'date'); // Default: date
        $sortOrder = $request->query('order', 'desc'); // Default: most recent first

        if ($sortBy === 'name') {
            $query->orderBy('name', $sortOrder);
        } else {
            $query->orderBy('created_at', $sortOrder);
        }

        // 📄 Pagination
        $users = $query->paginate(10)->appends($request->query());

        return $this->ResponseJson(
            true,
            new UserCollection($users),
            'Users retrieved successfully',
            [
                'current_page' => $users->currentPage(),
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'last_page' => $users->lastPage(),
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        $userReqData = request()->user();

        if (!$user) {
            return $this->ResponseJson(false, null, 'User not found', null, 404);
        }

        if ($userReqData->role === 'user' && $user->id != $userReqData->id) {
            return $this->ResponseJson(false, null, "Unauthorized. Required role: admin", null, 403);
        }

        return $this->ResponseJson(true, new UserResource($user), 'User retrieved successfully');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $userReqData = $request->user();

        if (!$user) {
            return $this->ResponseJson(false, null, 'User not found', [], 404);
        }

        if ($userReqData->role === 'user' && $user->id != $userReqData->id) {
            return $this->ResponseJson(false, null, "Unauthorized. Required role: admin", null, 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|min:8|confirmed',
        ]);

        if ($request->has('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return $this->ResponseJson(true, new UserResource($user), 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $userReqData = request()->user();

        if (!$user) {
            return $this->ResponseJson(false, null, 'User not found', [], 404);
        }

        if ($userReqData->role === 'user' && $user->id != $userReqData->id) {
            return $this->ResponseJson(false, null, "Unauthorized. Required role: admin", null, 403);
        }

        $user->delete();

        return $this->ResponseJson(true, null, 'User deleted successfully');
    }
}
