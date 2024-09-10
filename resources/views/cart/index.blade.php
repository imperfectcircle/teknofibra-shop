<x-app-layout>
    <div class="container lg:w-2/3 xl:w-2/3 mx-auto">
        <h1 class="text-3xl font-bold mb-6">{{ __('cart.title') }}</h1>

        @if (session('error'))
            <div class="py-2 px-3 bg-red-500 text-white mb-3 rounded text-center">
                {{ session('error') }}
            </div>
        @endif

        <div x-data="{
            cartItems: {{
                json_encode(
                    $products->map(fn($product) => [
                        'id' => $product->id,
                        'slug' => $product->slug,
                        'image' => $product->image,
                        'title' => $product->title,
                        'title_en' => $product->title_en,
                        'price' => $product->price,
                        'quantity' => $cartItems[$product->id]['quantity'],
                        'href' => route('product.view', $product->slug),
                        'removeUrl' => route('cart.remove', $product),
                        'updateQuantityUrl' => route('cart.update-quantity', $product)
                    ])
                )
            }},
            get cartTotal() {
                return this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0).toFixed(2)
            },
            shippingCost: {{ $shippingCost }},
            
        }" class="bg-white p-4 rounded-lg shadow">
        <h2 class="title">Cart</h2>
            <!-- Product Items -->
            <template x-if="cartItems.length">
                <div>
                    <!-- Product Item -->
                    <template x-for="product of cartItems" :key="product.id">
                        <div x-data="productItem(product)">
                            <div
                                class="w-full flex flex-col sm:flex-row items-center gap-4 flex-1">
                                <a :href="product.href"
                                    class="w-36 h-32 flex items-center justify-center overflow-hidden">
                                    <img :src="product.image" class="object-cover" alt=""/>
                                </a>
                                <div class="flex flex-col justify-between flex-1 text-black">
                                    <div class="flex justify-between mb-3">
                                        <h3 x-text="{{ app()->getLocale() == 'en' ? 'product.title_en' : 'product.title' }}"></h3>
                                        <span class="text-lg font-semibold">
                                            €<span x-text="product.price"></span>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            {{ __('cart.quantity') }}:
                                            <input
                                                type="number"
                                                min="1"
                                                x-model="product.quantity"
                                                @change="changeQuantity()"
                                                class="ml-3 py-1 border-gray-200 focus:border-purple-600 focus:ring-purple-600 w-16 text-black"
                                            />
                                        </div>
                                        <a
                                            href="#"
                                            @click.prevent="removeItemFromCart()"
                                            class="text-purple-600 hover:text-purple-500"
                                        >{{ __('cart.remove') }}</a
                                        >
                                    </div>
                                </div>
                            </div>
                            <!--/ Product Item -->
                            <hr class="my-5"/>
                        </div>
                    </template>
                    <!-- Product Item -->

                    <!-- Aggiungi il componente per il codice sconto -->
                    @auth
                    <div id="discount-code-component" class="mb-4">
                        <div class="flex items-center justify-between mb-2 text-black">
                            <input type="text" id="discount-code-input" placeholder="Inserisci il codice sconto" class="border rounded px-2 py-1 w-2/3">
                            <button id="apply-discount-btn" class="btn-primary">Applica</button>
                        </div>
                        <p id="discount-message" class="text-sm"></p>
                    </div>
                    @endauth

                    <div class="border-t border-gray-300 pt-4">
                        <div class="flex justify-between text-black">
                            <span class="font-semibold">{{ __('cart.subtotal') }}</span>
                            <span id="cartSubtotal" class="text-xl" x-text="`€${cartTotal}`"></span>
                        </div>
                        <div id="discount-amount" class="flex justify-between mt-2 text-black" style="display: none;">
                            <span class="font-semibold">Sconto</span>
                            <span id="discountValue" class="text-xl text-green-600 font-semibold"></span>
                        </div>
                        @auth
                        <div class="flex justify-between mt-2 text-black">
                            <span class="font-semibold">{{ __('cart.shipping_costs') }}</span>
                            <span id="shippingCost" class="text-xl" x-text="`€${shippingCost.toFixed(2)}`"></span>
                        </div>
                        <div class="flex justify-between mt-2 mb-3 text-black">
                            <span class="font-semibold">{{ __('cart.total') }}</span>
                            <span id="cartTotal" class="text-xl font-semibold" x-text="`€${(parseFloat(cartTotal) + parseFloat(shippingCost)).toFixed(2)}`"></span>
                        </div>
                        <form action="{{ route('checkout') }}" method="post">
                            @csrf
                            <input type="hidden" name="discount_code" id="discount-code-hidden">
                            <button type="submit" class="btn-primary w-full py-3 text-lg">
                                {{ __('cart.payment') }}
                            </button>
                        </form>
                        @else
                        <a class="text-center inline-block btn-primary w-full py-3 text-lg mt-5" href="{{ route('login') }}">Accedi per Continuare</a>
                        @endauth
                        
                    </div>
                </div>
                <!--/ Product Items -->
            </template>
            <template x-if="!cartItems.length">
                <div class="text-center py-8 text-gray-500">
                    {{ __('cart.no_items') }}
                </div>
            </template>

        </div>
    </div>

    
</x-app-layout>