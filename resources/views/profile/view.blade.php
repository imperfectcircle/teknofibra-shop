<x-app-layout>
    <div x-data="{
            flashMessage: '{{\Illuminate\Support\Facades\Session::get('flash_message')}}',
            init() {
                if (this.flashMessage) {
                    setTimeout(() => this.$dispatch('notify', {message: this.flashMessage}), 200)
                }
            }
        }" class="container mx-auto lg:w-2/3 p-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            <div class="bg-white p-3 shadow rounded-lg md:col-span-2">
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
                    <h2 class="text-xl font-semibold mb-2">Dettagli Profilo</h2>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div class="">
                            <x-text-input
                            type="text"
                            name="first_name"
                            value="{{old('first_name', $customer->first_name)}}"
                            placeholder="Nome"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>
                        <div class="">
                            <x-text-input
                            type="text"
                            name="last_name"
                            value="{{old('last_name', $customer->last_name)}}"
                            placeholder="Cognomee"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <x-text-input
                            type="text"
                            name="email"
                            value="{{old('email', $user->email)}}"
                            placeholder="Indirizzo Email"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="mb-3">
                        <x-text-input
                            type="text"
                            name="phone"
                            value="{{old('phone', $customer->phone)}}"
                            placeholder="Telefono"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <h2 class="text-xl mt-6 font-semibold mb-2">Indirizzo di Fatturazione</h2>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-text-input
                                type="text"
                                name="billing[address1]"
                                x-model="billingAddress.address1"
                                placeholder="Indirizzo"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('billing.address1')" class="mt-2" />
                        </div>
                        <div>
                            <x-text-input
                                type="text"
                                name="billing[address2]"
                                x-model="billingAddress.address2"
                                placeholder="Num. Civico"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('billing.address2')" class="mt-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-text-input
                                type="text"
                                name="billing[city]"
                                x-model="billingAddress.city"
                                placeholder="Città"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('billing.city')" class="mt-2" />
                        </div>
                        <div>
                            <x-text-input
                                type="text"
                                name="billing[zipcode]"
                                x-model="billingAddress.zipcode"
                                placeholder="CAP"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('billing.zipcode')" class="mt-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-text-input type="select"
                                     name="billing[country_code]"
                                     x-model="billingAddress.country_code"
                                     class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded">
                                <option value="">Seleziona il Paese</option>
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
                                        x-model="billingAddress.state"
                                        class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded">
                                    <option value="">Seleziona lo Stato</option>
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
                                    x-model="billingAddress.state"
                                    placeholder="Stato"
                                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                                />
                                <x-input-error :messages="$errors->get('billing.state')" class="mt-2" />
                            </template>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6 mb-2">
                        <h2 class="text-xl font-semibold">Indirizzo di Spedizione</h2>
                        <label for="sameAsBillingAddress" class="text-gray-700">
                            <input @change="$event.target.checked ? shippingAddress = {...billingAddress} : ''"
                                id="sameAsBillingAddress" type="checkbox"
                                class="text-purple-600 focus:ring-purple-600 mr-2"> Uguale all'indirizzo di Fatturazione
                        </label>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-text-input
                                type="text"
                                name="shipping[address1]"
                                x-model="shippingAddress.address1"
                                placeholder="Indirizzo"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('shipping.address1')" class="mt-2" />
                        </div>
                        <div>
                            <x-text-input
                                type="text"
                                name="shipping[address2]"
                                x-model="shippingAddress.address2"
                                placeholder="Num. civico"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('shipping.address2')" class="mt-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-text-input
                                type="text"
                                name="shipping[city]"
                                x-model="shippingAddress.city"
                                placeholder="Città"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input-error :messages="$errors->get('shipping.city')" class="mt-2" />
                        </div>
                        <div>
                            <x-text-input
                                name="shipping[zipcode]"
                                x-model="shippingAddress.zipcode"
                                type="text"
                                placeholder="CAP"
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
                                <option value="">Seleziona il Paese</option>
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
                                    <option value="">Seleziona lo Stato</option>
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
                                    placeholder="Stato"
                                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                                />
                                <x-input-error :messages="$errors->get('shipping.state')" class="mt-2" />
                            </template>
                        </div>
                    </div>

                    <x-primary-button class="w-full">Aggiorna</x-primary-button>
                </form>
            </div>
            <div class="bg-white p-3 shadow rounded-lg">
                <form action="{{route('profile_password.update')}}" method="post">
                    @csrf
                    <h2 class="text-xl font-semibold mb-2">Aggiona Password</h2>
                    <div class="mb-3">
                        <x-text-input
                            type="password"
                            name="old_password"
                            placeholder="La Password Attuale"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                        <x-input-error :messages="$errors->get('old_password')" class="mt-2" />
                    </div>
                    <div class="mb-3">
                        <x-text-input
                            type="password"
                            name="new_password"
                            placeholder="Nuova Password"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                        <x-input-error :messages="$errors->get('new_password')" class="mt-2" />
                    </div>
                    <div class="mb-3">
                        <x-text-input
                            type="password"
                            name="new_password_confirmation"
                            placeholder="Conferma la nuova Password"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                    </div>
                    <x-primary-button>Aggiorna</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>