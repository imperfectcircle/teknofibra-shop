<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingCost;
use Illuminate\Http\Request;

class ShippingCostController extends Controller
{
    public function index() {
        $countries = Country::where('active', true)->with('shippingCost')->get();
        return response()->json($countries);
    }

    public function update(Request $request) {
        $data = $request->validate([
            'costs' => 'required|array',
            'costs.*.country_code' => 'required|exists:countries,code,active,1',
            'costs.*.cost' => 'required|numeric|min:0',
        ]);

        foreach ($data['costs'] as $cost) {
            ShippingCost::updateOrCreate(
                ['country_code' => $cost['country_code']],
                ['cost' => $cost['cost']]
            );
        }

        return response()->json(['message' => 'Spese di spedizione aggiornate con successo']);
    }
}
