<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;

class GeoController extends Controller
{
    protected $baseUrl;

    public function __construct() {
        $this->baseUrl = config('services.gateway.url') . '/geo/api/v1';
    }

    /**
     * Display masters (Countries & States)
     */
    public function indexMasters(): Response
    {
        $token = session('remote_token');
        
        try {
            $countriesRes = Http::withToken($token)->timeout(10)
                ->get("{$this->baseUrl}/countries");
            $countries = $countriesRes->successful() ? $countriesRes->json() : [];

            $statesRes = Http::withToken($token)->timeout(10)
                ->get("{$this->baseUrl}/states");
            $states = $statesRes->successful() ? $statesRes->json() : [];

        } catch (\Exception $e) {
            \Log::error("Failed to fetch masters: " . $e->getMessage());
            $countries = [];
            $states = [];
        }

        return Inertia::render('Geo/Masters/Index', [
            'countries' => $countries,
            'states' => $states,
        ]);
    }

    /**
     * Countries CRUD
     */
    public function storeCountry(Request $request)
    {
        $token = session('remote_token');
        $response = Http::withToken($token)->post("{$this->baseUrl}/countries", $request->all());
        return $response->successful() ? back()->with('success', 'Country added') : back()->withErrors(['message' => 'Failed']);
    }

    public function updateCountry(Request $request, $id)
    {
        $token = session('remote_token');
        $response = Http::withToken($token)->put("{$this->baseUrl}/countries/{$id}", $request->all());
        return $response->successful() ? back()->with('success', 'Country updated') : back()->withErrors(['message' => 'Failed']);
    }

    public function destroyCountry($id)
    {
        $token = session('remote_token');
        $response = Http::withToken($token)->patch("{$this->baseUrl}/countries/{$id}");
        return $response->successful() ? back()->with('success', 'Status updated') : back()->withErrors(['message' => 'Failed']);
    }

    /**
     * States CRUD
     */
    public function storeState(Request $request)
    {
        $token = session('remote_token');
        $response = Http::withToken($token)->post("{$this->baseUrl}/states", $request->all());
        return $response->successful() ? back()->with('success', 'State added') : back()->withErrors(['message' => 'Failed']);
    }

    public function updateState(Request $request, $id)
    {
        $token = session('remote_token');
        $response = Http::withToken($token)->put("{$this->baseUrl}/states/{$id}", $request->all());
        return $response->successful() ? back()->with('success', 'State updated') : back()->withErrors(['message' => 'Failed']);
    }

    public function destroyState($id)
    {
        $token = session('remote_token');
        $response = Http::withToken($token)->patch("{$this->baseUrl}/states/{$id}");
        return $response->successful() ? back()->with('success', 'Status updated') : back()->withErrors(['message' => 'Failed']);
    }

    /**
     * Display a listing of cities.
     */
    public function indexCities(): Response
    {
        $token = session('remote_token');
        
        try {
            $response = Http::withToken($token)->timeout(15)
                ->get("{$this->baseUrl}/cities");
            $cities = $response->successful() ? $response->json() : [];

            $countriesRes = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/countries");
            $countries = $countriesRes->successful() ? $countriesRes->json() : [];

        } catch (\Exception $e) {
            \Log::error("Failed to fetch cities: " . $e->getMessage());
            $cities = [];
            $countries = [];
        }

        return Inertia::render('Geo/Cities/Index', [
            'cities' => $cities,
            'countries' => $countries, // We'll need this for the form
        ]);
    }

    /**
     * Store a newly created city.
     */
    public function storeCity(Request $request)
    {
        $token = session('remote_token');

        $response = Http::withToken($token)->timeout(15)
            ->post("{$this->baseUrl}/cities", $request->all());

        if ($response->successful()) {
            return Redirect::route('cities.index')->with('success', 'City created successfully.');
        }

        return back()->withErrors(['message' => 'Failed to create city.']);
    }

    /**
     * Update the specified city.
     */
    public function updateCity(Request $request, string $id)
    {
        $token = session('remote_token');
        
        $response = Http::withToken($token)->timeout(15)
            ->put("{$this->baseUrl}/cities/{$id}", $request->all());

        if ($response->successful()) {
            return Redirect::route('cities.index')->with('success', 'City updated successfully.');
        }

        return back()->withErrors(['message' => 'Failed to update city.']);
    }

    /**
     * Remove the specified city.
     */
    public function destroyCity(string $id)
    {
        $token = session('remote_token');

        \Log::info("Attempting to toggle city status: ID {$id}");
        $response = Http::withToken($token)->timeout(15)
            ->patch("{$this->baseUrl}/cities/{$id}");

        if ($response->successful()) {
            \Log::info("City status toggle successful for ID {$id}");
            return Redirect::route('cities.index')->with('success', 'City status updated successfully.');
        }

        \Log::error("Failed to toggle city status for ID {$id}. Response: " . $response->body());
        return back()->withErrors(['message' => 'Failed to update city status.']);
    }

    /**
     * Display a listing of service areas.
     */
    public function indexAreas(): Response
    {
        $token = session('remote_token');
        
        try {
            // Need cities for the creation form
            $citiesRes = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/cities");
            $cities = $citiesRes->successful() ? $citiesRes->json() : [];

            $areasRes = Http::withToken($token)->timeout(5)
                ->get("{$this->baseUrl}/areas");
            $areas = $areasRes->successful() ? $areasRes->json() : [];

        } catch (\Exception $e) {
            \Log::error("Failed to fetch geo data: " . $e->getMessage());
            $cities = [];
            $areas = [];
        }

        return Inertia::render('Geo/Areas/Index', [
            'cities' => $cities,
            'areas' => $areas,
        ]);
    }

    /**
     * Store a newly created service area.
     */
    public function storeArea(Request $request)
    {
        $token = session('remote_token');

        $response = Http::withToken($token)->timeout(15)
            ->post("{$this->baseUrl}/areas", $request->all());

        if ($response->successful()) {
            return Redirect::route('areas.index')->with('success', 'Service area created successfully.');
        }

        return back()->withErrors(['message' => 'Failed to create service area.']);
    }

    /**
     * Update the specified area.
     */
    public function updateArea(Request $request, string $id)
    {
        $token = session('remote_token');
        
        $response = Http::withToken($token)->timeout(15)
            ->put("{$this->baseUrl}/areas/{$id}", $request->all());

        if ($response->successful()) {
            return Redirect::route('areas.index')->with('success', 'Service area updated successfully.');
        }

        return back()->withErrors(['message' => 'Failed to update service area.']);
    }

    /**
     * Remove the specified service area.
     */
    public function destroyArea(string $id)
    {
        $token = session('remote_token');

        \Log::info("Attempting to toggle service area status: ID {$id}");
        $response = Http::withToken($token)->timeout(15)
            ->patch("{$this->baseUrl}/areas/{$id}");

        if ($response->successful()) {
            \Log::info("Service area status toggle successful for ID {$id}");
            return Redirect::route('areas.index')->with('success', 'Service area status updated successfully.');
        }

        \Log::error("Failed to toggle service area status for ID {$id}. Response: " . $response->body());
        return back()->withErrors(['message' => 'Failed to update service area status.']);
    }
}
