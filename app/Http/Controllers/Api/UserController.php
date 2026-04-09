<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user   = $request->user();
        $client = $user->client;

        return response()->json([
            'data' => [
                'id'              => $user->id,
                'name'            => $user->name,
                'email'           => $user->email,
                'phone'           => $client?->phone ?? $user->phone,
                'role'            => $user->role,
                'avatar'          => $client?->profile_picture
                    ? config('app.url') . '/storage/' . ltrim(preg_replace('/^(app\/)?public\//', '', $client->profile_picture), '/')
                    : null,
                'city'            => $client?->city,
                'address'         => $client?->address,
                'nationality'     => $client?->nationality,
                'gender'          => $client?->gender,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'    => 'sometimes|string|max:255',
            'phone'   => 'sometimes|string|max:20',
            'city'    => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:500',
        ]);

        $user = $request->user();

        if ($request->filled('name')) {
            $user->update(['name' => $request->name]);
        }

        if ($user->client) {
            $user->client->update(
                $request->only(['phone', 'city', 'address'])
            );
        }

        return response()->json([
            'message' => 'Profil mis à jour avec succès.',
            'data'    => [
                'id'      => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'phone'   => $user->client?->phone ?? $user->phone,
                'role'    => $user->role,
                'city'    => $user->client?->city,
                'address' => $user->client?->address,
            ],
        ]);
    }
}
