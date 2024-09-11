<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    public function index() {
        $countries = Country::all();
        return response()->json($countries);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'code' => 'required|string|max:3|unique:countries,code',
            'name' => 'required|string|max:255',
            'states' => 'nullable|json',
            'active' => 'boolean',
        ]);

        $country = Country::create($validatedData);
        return response()->json($country, 201);
    }

    public function show(Country $country) {
        return response()->json($country);
    }

    public function update(Request $request, Country $country) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'states' => 'nullable|json',
            'active' => 'boolean',
        ]);

        $country->update($validatedData);
        return response()->json($country);
    }

    public function destroy(Country $country) {
        $country->delete();
        return response()->json(null, 204);
    }
}
