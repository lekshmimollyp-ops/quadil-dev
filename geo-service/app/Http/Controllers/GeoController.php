<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\ServiceArea;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    /**
     * Create a new City
     */
    public function createCity(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:cities,name',
            'state_id' => 'nullable|exists:states,id',
            'state' => 'nullable|string',
            'country_code' => 'nullable|string|max:3',
        ]);

        $city = City::create($request->all());

        return response()->json([
            'message' => 'City created successfully',
            'city' => $city
        ], 201);
    }

    /**
     * Update an existing City
     */
    public function updateCity(Request $request, $id)
    {
        $city = City::findOrFail($id);
        $request->validate([
            'name' => 'required|string|unique:cities,name,' . $id,
            'state_id' => 'nullable|exists:states,id',
            'state' => 'nullable|string',
            'country_code' => 'nullable|string|max:3',
        ]);

        $city->update($request->all());

        return response()->json([
            'message' => 'City updated successfully',
            'city' => $city
        ]);
    }

    /**
     * Create a new Service Area (Radius-based for now)
     */
    public function createArea(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string',
            'center_lat' => 'required|numeric',
            'center_lng' => 'required|numeric',
            'radius_km' => 'required|numeric',
        ]);

        $area = ServiceArea::create($request->all());

        return response()->json([
            'message' => 'Service area created successfully',
            'area' => $area
        ], 201);
    }

    /**
     * Update an existing Service Area
     */
    public function updateArea(Request $request, $id)
    {
        $area = ServiceArea::findOrFail($id);
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string',
            'center_lat' => 'required|numeric',
            'center_lng' => 'required|numeric',
            'radius_km' => 'required|numeric',
        ]);

        $area->update($request->all());

        return response()->json([
            'message' => 'Service area updated successfully',
            'area' => $area
        ]);
    }

    /**
     * Find areas matching a Lat/Lng point
     */
    public function checkCoverage(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = $request->lat;
        $lng = $request->lng;

        // Simplify check using Haversine formula for radius-based areas
        // In a real production app, we would use PostGIS for polygon checks
        $areas = ServiceArea::where('is_active', true)->get()->filter(function ($area) use ($lat, $lng) {
            if (!$area->center_lat || !$area->center_lng || !$area->radius_km) {
                return false;
            }

            $distance = $this->haversineDistance(
                $lat, $lng, 
                (float)$area->center_lat, (float)$area->center_lng
            );

            return $distance <= (float)$area->radius_km;
        });

        return response()->json([
            'in_coverage' => $areas->count() > 0,
            'matching_areas' => $areas->values()
        ]);
    }

    /**
     * Admin Endpoint: List all cities
     */
    public function indexCities()
    {
        $cities = City::with(['state.country'])->orderBy('name', 'asc')->get();
        return response()->json($cities);
    }

    /**
     * Admin Endpoint: List all countries
     */
    public function indexCountries()
    {
        $countries = Country::with('states')->orderBy('name', 'asc')->get();
        return response()->json($countries);
    }

    /**
     * Admin Endpoint: Create country
     */
    public function createCountry(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:countries,name',
            'code' => 'required|string|unique:countries,code',
        ]);

        $country = Country::create($request->all());
        return response()->json($country, 201);
    }

    /**
     * Admin Endpoint: Update country
     */
    public function updateCountry(Request $request, $id)
    {
        $country = Country::findOrFail($id);
        $request->validate([
            'name' => 'required|string|unique:countries,name,' . $id,
            'code' => 'required|string|unique:countries,code,' . $id,
        ]);

        $country->update($request->all());
        return response()->json($country);
    }

    /**
     * Admin Endpoint: Toggle country status
     */
    public function toggleCountry($id)
    {
        $country = Country::findOrFail($id);
        $country->update(['is_active' => !$country->is_active]);
        return response()->json($country);
    }

    /**
     * Admin Endpoint: List all states
     */
    public function indexStates(Request $request)
    {
        $query = State::with('country')->orderBy('name', 'asc');
        
        if ($request->has('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        return response()->json($query->get());
    }

    /**
     * Admin Endpoint: Create state
     */
    public function createState(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string',
        ]);

        $state = State::create($request->all());
        return response()->json($state, 201);
    }

    /**
     * Admin Endpoint: Update state
     */
    public function updateState(Request $request, $id)
    {
        $state = State::findOrFail($id);
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string',
        ]);

        $state->update($request->all());
        return response()->json($state);
    }

    /**
     * Admin Endpoint: Toggle state status
     */
    public function toggleState($id)
    {
        $state = State::findOrFail($id);
        $state->update(['is_active' => !$state->is_active]);
        return response()->json($state);
    }

    /**
     * Admin Endpoint: Delete a city
     */
    public function deleteCity($id)
    {
        $city = City::findOrFail($id);
        $city->update(['is_active' => !$city->is_active]);
        
        $status = $city->is_active ? 'activated' : 'deactivated';
        return response()->json(['message' => "City {$status} successfully", 'city' => $city]);
    }

    /**
     * Admin Endpoint: List all service areas
     */
    public function indexAreas()
    {
        $areas = ServiceArea::with('city')->orderBy('name', 'asc')->get();
        return response()->json($areas);
    }

    /**
     * Admin Endpoint: Delete a service area
     */
    public function deleteArea($id)
    {
        $area = ServiceArea::findOrFail($id);
        $area->update(['is_active' => !$area->is_active]);

        $status = $area->is_active ? 'activated' : 'deactivated';
        return response()->json(['message' => "Service area {$status} successfully", 'area' => $area]);
    }

    /**
     * Haversine Distance Formula (KM)
     */
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
