<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone' => ['required', 'min:7'],
            'email' => ['required', 'email'],
            'vat.countryCode' => ['required', 'string', 'min:2'],
            'vat.vatNumber' => ['required', 'string'],
            'shipping.address1' => ['required'],
            'shipping.address2' => ['required'],
            'shipping.city' => ['required'],
            'shipping.state' => ['required'],
            'shipping.zipcode' => ['required'],
            'shipping.country_code' => ['required', 'exists:countries,code,active,1'],

            'billing.address1' => ['required'],
            'billing.address2' => ['required'],
            'billing.city' => ['required'],
            'billing.state' => ['required'],
            'billing.zipcode' => ['required'],
            'billing.country_code' => ['required', 'exists:countries,code,active,1'],

        ];
    }

    public function attributes()
    {
        return [
            'vat.countryCode' => 'Codice Paese',
            'vat.vatNumber' => 'Numero Partita IVA',
            'billing.address1' => 'Indirizzo',
            'billing.address2' => 'Num. Civico',
            'billing.city' => 'Città',
            'billing.state' => 'Stato/Provincia',
            'billing.zipcode' => 'CAP',
            'billing.country_code' => 'Nazione',
            'shipping.address1' => 'Indirizzo',
            'shipping.address2' => 'Num. Civico',
            'shipping.city' => 'Città',
            'shipping.state' => 'Stato/Provincia',
            'shipping.zipcode' => 'CAP',
            'shipping.country_code' => 'Paese',
        ];
    }
}
