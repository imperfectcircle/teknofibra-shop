<?php

namespace App\Http\Requests;

use App\Enums\CustomerStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone' => ['required', 'min:7'],
            'email' => ['required', 'email'],
            'status' => ['required', 'boolean'],

            'shippingAddress.address1' => ['required'],
            'shippingAddress.address2' => ['required'],
            'shippingAddress.city' => ['required'],
            'shippingAddress.state' => ['required'],
            'shippingAddress.zipcode' => ['required'],
            'shippingAddress.country_code' => ['required', 'exists:countries,code'],

            'billingAddress.address1' => ['required'],
            'billingAddress.address2' => ['required'],
            'billingAddress.city' => ['required'],
            'billingAddress.state' => ['required'],
            'billingAddress.zipcode' => ['required'],
            'billingAddress.country_code' => ['required', 'exists:countries,code'],

        ];
    }

    public function attributes()
    {
        return [
            'billingAddress.address1' => 'Indirizzo',
            'billingAddress.address2' => 'Num. Civico',
            'billingAddress.city' => 'Città',
            'billingAddress.state' => 'Stato/Provincia',
            'billingAddress.zipcode' => 'CAP',
            'billingAddress.country_code' => 'Nazione',
            'shippingAddress.address1' => 'Indirizzo',
            'shippingAddress.address2' => 'Num. Civico',
            'shippingAddress.city' => 'città',
            'shippingAddress.state' => 'Stato/Provincia',
            'shippingAddress.zipcode' => 'CAP',
            'shippingAddress.country_code' => 'Nazione',
        ];
    }
}