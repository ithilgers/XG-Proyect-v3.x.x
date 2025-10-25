<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Planet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanetController extends Controller
{
    /**
     * Get all planets for authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $planets = Planet::where('user_id', $user->user_id)
            ->orderBy('planet_galaxy')
            ->orderBy('planet_system')
            ->orderBy('planet_planet')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $planets->map(function ($planet) {
                return [
                    'id' => $planet->planet_id,
                    'name' => $planet->planet_name,
                    'coordinates' => $planet->coordinates,
                    'type' => $planet->planet_type,
                    'resources' => [
                        'metal' => (float) $planet->planet_metal,
                        'crystal' => (float) $planet->planet_crystal,
                        'deuterium' => (float) $planet->planet_deuterium,
                    ],
                    'production' => $planet->planet_production ?? [],
                    'fields' => [
                        'current' => $planet->planet_field_current,
                        'max' => $planet->planet_field_max,
                    ],
                    'temperature' => [
                        'min' => $planet->planet_temp_min,
                        'max' => $planet->planet_temp_max,
                    ],
                    'last_update' => $planet->planet_last_update->toIso8601String(),
                ];
            }),
        ]);
    }

    /**
     * Get specific planet details.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $planet = Planet::where('planet_id', $id)
            ->where('user_id', $user->user_id)
            ->firstOrFail();

        // Update resources before returning
        $planet->updateResources();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $planet->planet_id,
                'name' => $planet->planet_name,
                'coordinates' => [
                    'galaxy' => $planet->planet_galaxy,
                    'system' => $planet->planet_system,
                    'planet' => $planet->planet_planet,
                    'formatted' => $planet->coordinates,
                ],
                'type' => $planet->planet_type,
                'resources' => [
                    'metal' => (float) $planet->planet_metal,
                    'crystal' => (float) $planet->planet_crystal,
                    'deuterium' => (float) $planet->planet_deuterium,
                ],
                'production' => $planet->planet_production ?? [
                    'metal' => 0,
                    'crystal' => 0,
                    'deuterium' => 0,
                ],
                'fields' => $planet->planet_fields ?? [],
                'debris' => $planet->planet_debris ?? [],
                'info' => [
                    'image' => $planet->planet_image,
                    'diameter' => $planet->planet_diameter,
                    'fields_used' => $planet->planet_field_current,
                    'fields_max' => $planet->planet_field_max,
                    'temp_min' => $planet->planet_temp_min,
                    'temp_max' => $planet->planet_temp_max,
                ],
                'last_update' => $planet->planet_last_update->toIso8601String(),
                'created_at' => $planet->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Update planet (e.g., rename).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $planet = Planet::where('planet_id', $id)
            ->where('user_id', $user->user_id)
            ->firstOrFail();

        $validated = $request->validate([
            'planet_name' => 'sometimes|string|max:20|min:3',
        ]);

        $planet->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Planet updated successfully',
            'data' => [
                'id' => $planet->planet_id,
                'name' => $planet->planet_name,
                'coordinates' => $planet->coordinates,
            ],
        ]);
    }
}
