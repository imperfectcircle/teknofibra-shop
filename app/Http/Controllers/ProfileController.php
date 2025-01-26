<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Enums\AddressType;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\PasswordUpdateRequest;

class ProfileController extends Controller
{
    public function view(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        $customer = $user->customer;
        $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
        $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);
        $countries = Country::query()->where('active', true)->orderBy('name')->get();
        return view('profile.view', compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
    }

    public function store(ProfileRequest $request)
    {
        $customerData = $request->validated();
        $shippingData = $customerData['shipping'];
        $billingData = $customerData['billing'];
        $vatData = $customerData['vat'];

        // Elenco dei codici paese dell'Unione Europea
        $euCountries = ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'EL', 'ES', 'FI', 'FR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK'];

        // Verifica partita IVA
        if (in_array(strtoupper($vatData['countryCode']), $euCountries)) {
            $response = Http::post('https://ec.europa.eu/taxation_customs/vies/rest-api/check-vat-number', [
            'countryCode' => $vatData['countryCode'],
            'vatNumber' => $vatData['vatNumber'],
            ]);
            
            if (!$response->json('valid')) {
                return back()->with('error' ,__('vat.not_valid'));
            }
        }

        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        $customer = $user->customer;

        DB::beginTransaction();
        try {
            $customer->update(array_merge($customerData, [
                'vat_country_code' => $vatData['countryCode'],
                'vat_number' => $vatData['vatNumber'],
            ]));

            if ($customer->shippingAddress) {
                $customer->shippingAddress->update($shippingData);
            } else {
                $shippingData['customer_id'] = $customer->user_id;
                $shippingData['type'] = AddressType::Shipping->value;
                CustomerAddress::create($shippingData);
            }
            if ($customer->billingAddress) {
                $customer->billingAddress->update($billingData);
            } else {
                $billingData['customer_id'] = $customer->user_id;
                $billingData['type'] = AddressType::Billing->value;
                CustomerAddress::create($billingData);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            Log::critical(__METHOD__ . ' method does not work' . $e->getMessage());
            throw $e;
        }
        DB::commit();

        $request->session()->flash('flash_message', 'Profilo aggiornato con successo.');

        return redirect()->route('profile');

    }

    public function passwordUpdate(PasswordUpdateRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $passwordData = $request->validated();

        $user->password = Hash::make($passwordData['new_password']);
        $user->save();

        $request->session()->flash('flash_message', 'Password aggiornata con successo.');

        return redirect()->route('profile');
    }
}