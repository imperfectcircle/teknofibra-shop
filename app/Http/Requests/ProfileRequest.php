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

            'shipping.address1' => ['required'],
            'shipping.address2' => ['required'],
            'shipping.city' => ['required'],
            'shipping.state' => ['required'],
            'shipping.zipcode' => ['required'],
            'shipping.country_code' => ['required', 'exists:countries,code'],

            'billing.address1' => ['required'],
            'billing.address2' => ['required'],
            'billing.city' => ['required'],
            'billing.state' => ['required'],
            'billing.zipcode' => ['required'],
            'billing.country_code' => ['required', 'exists:countries,code'],

        ];
    }

    public function attributes()
    {
        return [
            'billing.address1' => 'Indirizzo 1',
            'billing.address2' => 'Indirizzo 2',
            'billing.city' => 'Città',
            'billing.state' => 'Stato',
            'billing.zipcode' => 'CAP',
            'billing.country_code' => 'Paese',
            'shipping.address1' => 'Indirizzo 1',
            'shipping.address2' => 'Indirizzo 2',
            'shipping.city' => 'Città',
            'shipping.state' => 'Stato',
            'shipping.zipcode' => 'CAP',
            'shipping.country_code' => 'Paese',
        ];
    }
}
