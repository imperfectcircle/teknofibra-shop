<x-app-layout>
    <x-slot:title>Teknofibra Shop | Profile</x-slot:title>
    <x-slot:description>Gestisci facilmente i tuoi dati di spedizione e fatturazione e aggiorna la tua password su questa pagina. Inserisci nuovi indirizzi o modifica quelli esistenti per una consegna rapida e sicura. Mantieni le tue informazioni aggiornate e assicurati un'esperienza d'acquisto comoda e personalizzata.</x-slot:description>
    <x-slot:canonical>https://shop.teknofibra.it/profile</x-slot:canonical>
    <div x-data="{
            flashMessage: '{{\Illuminate\Support\Facades\Session::get('flash_message')}}',
            init() {
                if (this.flashMessage) {
                    setTimeout(() => this.$dispatch('notify', {message: this.flashMessage}), 200)
                }
            }
        }" class="container mx-auto lg:w-2/3 p-5">
        @if (session('error'))
            <div class="py-2 px-3 bg-red-500 text-white mb-3 rounded text-center">
                {{ session('error') }}
            </div>
        @endif
        <h2 class="title">Profile</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            <div class="bg-white text-black p-3 shadow rounded-lg md:col-span-2">
                <form x-data="{
                    countries: {{ json_encode($countries) }},
                    billingAddress: {{ json_encode([
                        'address1' => old('billing.address1', $billingAddress->address1),
                        'address2' => old('billing.address2', $billingAddress->address2),
                        'city' => old('billing.city', $billingAddress->city),
                        'state' => old('billing.state', $billingAddress->state),
                        'country_code' => old('billing.country_code', $billingAddress->country_code),
                        'zipcode' => old('billing.zipcode', $billingAddress->zipcode),
                    ]) }},
                    shippingAddress: {{ json_encode([
                        'address1' => old('shipping.address1', $shippingAddress->address1),
                        'address2' => old('shipping.address2', $shippingAddress->address2),
                        'city' => old('shipping.city', $shippingAddress->city),
                        'state' => old('shipping.state', $shippingAddress->state),
                        'country_code' => old('shipping.country_code', $shippingAddress->country_code),
                        'zipcode' => old('shipping.zipcode', $shippingAddress->zipcode),
                    ]) }},
                    get billingCountryStates() {
                        const country = this.countries.find(c => c.code === this.billingAddress.country_code)
                        if (country && country.states) {
                            return JSON.parse(country.states);
                        }
                        return null;
                    },
                    get shippingCountryStates() {
                        const country = this.countries.find(c => c.code === this.shippingAddress.country_code)
                        if (country && country.states) {
                            return JSON.parse(country.states);
                        }
                        return null;
                    }
                }" action="{{ route('profile.update') }}" method="post">
                    @csrf
                    <h2 class="text-xl font-semibold mb-5 text-black">{{ __('profile.profile_details') }}</h2>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div class="">
                            <label for="first_name">{{ __('profile.profile_name') }}</label>
                            <x-text-input
                            type="text"
                            name="first_name"
                            id="first_name"
                            value="{{old('first_name', $customer->first_name)}}"
                            placeholder="{{ __('profile.profile_name') }}"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>
                        <div class="">
                            <label for="last_name">{{ __('profile.profile_lastname') }}</label>
                            <x-text-input
                            type="text"
                            name="last_name"
                            id="last_name"
                            value="{{old('last_name', $customer->last_name)}}"
                            placeholder="{{ __('profile.profile_lastname') }}"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email">{{ __('profile.profile_email') }}</label>
                        <x-text-input
                            type="text"
                            name="email"
                            id="email"
                            value="{{old('email', $user->email)}}"
                            placeholder="{{ __('profile.profile_email') }}"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="mb-3">
                        <label for="phone">{{ __('profile.profile_phone') }}</label>
                        <x-text-input
                            type="text"
                            name="phone"
                            id="phone"
                            value="{{old('phone', $customer->phone)}}"
                            placeholder="{{ __('profile.profile_phone') }}"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                    <div class="flex">
                        <div class="mr-3">
                            <label for="vat[countryCode]">{{ __('profile.profile_country_code') }}</label>
                            <x-text-input
                                type="text"
                                name="vat[countryCode]"
                                id="vat[countryCode]"
                                value="{{old('vat[countryCode]', $customer->vat_country_code)}}"
                                placeholder="{{ __('profile.profile_country_code') }}"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('vat.countryCode')" class="mt-2" />
                        </div>
    
                        <div>
                            <label for="vat[vatNumber]">{{ __('profile.profile_vat_number') }}</label>
                            <x-text-input
                            type="text"
                            name="vat[vatNumber]"
                            id="vat[vatNumber]"
                            value="{{old('vat[vatNumber]', $customer->vat_number)}}"
                            placeholder="{{ __('profile.profile_vat_number') }}"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('vat.vatNumber')" class="mt-2" />
                        </div>
                    </div>
                    <h2 class="text-xl mt-6 font-semibold mb-2 text-black">{{ __('profile.billing_address') }}</h2>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label for="billing[address1]">{{ __('profile.profile_address') }}</label>
                            <x-text-input
                                type="text"
                                name="billing[address1]"
                                id="billing[address1]"
                                x-model="billingAddress.address1"
                                placeholder="{{ __('profile.profile_address') }}"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('billing.address1')" class="mt-2" />
                        </div>
                        <div>
                            <label for="billing[address2]">{{ __('profile.profile_number') }}</label>
                            <x-text-input
                                type="text"
                                name="billing[address2]"
                                id="billing[address2]"
                                x-model="billingAddress.address2"
                                placeholder="{{ __('profile.profile_number') }}"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('billing.address2')" class="mt-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label for="billing[city]">{{ __('profile.profile_city') }}</label>
                            <x-text-input
                                type="text"
                                name="billing[city]"
                                id="billing[city]"
                                x-model="billingAddress.city"
                                placeholder="{{ __('profile.profile_city') }}"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('billing.city')" class="mt-2" />
                        </div>
                        <div>
                            <label for="billing[zipcode]">{{ __('profile.profile_zipcode') }}</label>
                            <x-text-input
                                type="text"
                                name="billing[zipcode]"
                                id="billing[zipcode]"
                                x-model="billingAddress.zipcode"
                                placeholder="{{ __('profile.profile_zipcode') }}"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('billing.zipcode')" class="mt-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-text-input type="select"
                                    name="billing[country_code]"
                                    id="billing[country_code]"
                                    x-model="billingAddress.country_code"
                                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded">
                                <option value="">{{ __('profile.profile_country') }}</option>
                                <template x-for="country of countries" :key="country.code">
                                    <option :selected="country.code === billingAddress.country_code"
                                            :value="country.code" x-text="country.name"></option>
                                </template>
                            </x-text-input>
                            <x-input-error :messages="$errors->get('billing.country_code')" class="mt-2" />
                        </div>
                        <div>
                            <template x-if="billingCountryStates">
                                <x-text-input type="select"
                                        name="billing[state]"
                                        id="billing[state]"
                                        x-model="billingAddress.state"
                                        class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded">
                                    <option value="">{{ __('profile.profile_state') }}</option>
                                    <template x-for="[code, state] of Object.entries(billingCountryStates)"
                                            :key="code">
                                        <option :selected="code === billingAddress.state"
                                                :value="code" x-text="state"></option>
                                    </template>
                                </x-text-input>
                                <x-input-error :messages="$errors->get('billing.state')" class="mt-2" />
                            </template>
                            <template x-if="!billingCountryStates">
                                
                                <x-text-input
                                    type="text"
                                    name="billing[state]"
                                    id="billing[province]"
                                    x-model="billingAddress.state"
                                    placeholder="{{ __('profile.profile_province') }}"
                                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                                />
                                <x-input-error :messages="$errors->get('billing.state')" class="mt-2" />
                            </template>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6 mb-2">
                        <h2 class="text-xl font-semibold text-black">{{ __('profile.shipping_address') }}</h2>
                        <label for="sameAsBillingAddress" class="text-gray-700">
                            <input @change="$event.target.checked ? shippingAddress = {...billingAddress} : ''"
                                id="sameAsBillingAddress" type="checkbox"
                                class="text-purple-600 focus:ring-purple-600 mr-2"> {{ __('profile.same_as') }}
                        </label>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label for="shipping[address1]">{{ __('profile.profile_address') }}</label>
                            <x-text-input
                                type="text"
                                name="shipping[address1]"
                                id="shipping[address1]"
                                x-model="shippingAddress.address1"
                                placeholder="{{ __('profile.profile_address') }}"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('shipping.address1')" class="mt-2" />
                        </div>
                        <div>
                            <label for="shipping[address2]">{{ __('profile.profile_number') }}</label>
                            <x-text-input
                                type="text"
                                name="shipping[address2]"
                                id="shipping[address2]"
                                x-model="shippingAddress.address2"
                                placeholder="{{ __('profile.profile_number') }}"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('shipping.address2')" class="mt-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label for="shipping[city]">{{ __('profile.profile_city') }}</label>
                            <x-text-input
                                type="text"
                                name="shipping[city]"
                                id="shipping[city]"
                                x-model="shippingAddress.city"
                                placeholder="{{ __('profile.profile_city') }}"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('shipping.city')" class="mt-2" />
                        </div>
                        <div>
                            <label for="shipping[zipcode]">{{ __('profile.profile_zipcode') }}</label>
                            <x-text-input
                                name="shipping[zipcode]"
                                id="shipping[zipcode]"
                                x-model="shippingAddress.zipcode"
                                type="text"
                                placeholder="{{ __('profile.profile_zipcode') }}"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('shipping.zipcode')" class="mt-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-text-input type="select"
                                     name="shipping[country_code]"
                                     x-model="shippingAddress.country_code"
                                     class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded">
                                <option value="">{{ __('profile.profile_country') }}</option>
                                <template x-for="country of countries" :key="country.code">
                                    <option :selected="country.code === shippingAddress.country_code"
                                            :value="country.code" x-text="country.name"></option>
                                </template>
                            </x-text-input>
                            <x-input-error :messages="$errors->get('shipping.country_code')" class="mt-2" />
                        </div>
                        <div>
                            <template x-if="shippingCountryStates">
                                <x-text-input type="select"
                                         name="shipping[state]"
                                         x-model="shippingAddress.state"
                                         class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded">
                                    <option value="">{{ __('profile.profile_state') }}</option>
                                    <template x-for="[code, state] of Object.entries(shippingCountryStates)"
                                              :key="code">
                                        <option :selected="code === shippingAddress.state"
                                                :value="code" x-text="state"></option>
                                    </template>
                                </x-text-input>
                                <x-input-error :messages="$errors->get('shipping.state')" class="mt-2" />
                            </template>
                            <template x-if="!shippingCountryStates">
                                <x-text-input
                                    type="text"
                                    name="shipping[state]"
                                    x-model="shippingAddress.state"
                                    placeholder="{{ __('profile.profile_province') }}"
                                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                                />
                                <x-input-error :messages="$errors->get('shipping.state')" class="mt-2" />
                            </template>
                        </div>
                    </div>

                    <x-primary-button class="w-full">{{ __('profile.profile_update') }}</x-primary-button>
                </form>
            </div>
            <div class="bg-white p-3 shadow rounded-lg">
                <form action="{{route('profile_password.update')}}" method="post">
                    @csrf
                    <h2 class="text-xl font-semibold mb-2 text-black">{{ __('profile.update_password') }}</h2>
                    <div class="mb-3">
                        <x-text-input
                            type="password"
                            name="old_password"
                            placeholder="{{ __('profile.current_password') }}"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                        <x-input-error :messages="$errors->get('old_password')" class="mt-2" />
                    </div>
                    <div class="mb-3">
                        <x-text-input
                            type="password"
                            name="new_password"
                            placeholder="{{ __('profile.new_password') }}"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                        <x-input-error :messages="$errors->get('new_password')" class="mt-2" />
                    </div>
                    <div class="mb-3">
                        <x-text-input
                            type="password"
                            name="new_password_confirmation"
                            placeholder="{{ __('profile.confirm_password') }}"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                    </div>
                    <x-primary-button>{{ __('profile.update_password') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>