<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DiscountController extends Controller
{
    public function index() {
        $discountCodes = DiscountCode::all();
        return response()->json($discountCodes);
    }

    public function store(Request $request) {
        $validateData = $request->validate([
            'code' => 'required|unique:discount_codes|max:250',
            'percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $discountCode = DiscountCode::create($validateData);
        return response()->json($discountCode, 201);
    }

    public function update(Request $request, DiscountCode $discountCode) {
        $validateData = $request->validate([
            'code' => [
                'required',
                'max:255',
                Rule::unique('discount_codes')->ignore($discountCode->id),
            ],
            'percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $discountCode->update($validateData);
        return response()->json(['message' => 'Codice Sconto aggiornato con successo']);
    }

    public function destroy(DiscountCode $discountCode) {
        $discountCode->delete();
        return response()->json(['message' => 'Codice Sconto eliminato con successo']);
    }
}
