<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function apply(Request $request) {
        $code = $request->input('code');
        $discountCode = DiscountCode::where('code', $code)
            ->where('is_active', true)
            ->first();

        if ($discountCode) {
            return response()->json([
                'success' => true,
                'percentage' => $discountCode->percentage
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Codice Sconto non valido o scaduto'
            ]);
        }
    }
}
