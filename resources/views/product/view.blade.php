<x-app-layout>
    <x-slot:title>Teknofibra Shop | {{ $product->title }}</x-slot:title>
    <x-slot:description>{{ $product->description }}</x-slot:description>
    <x-slot:canonical>https://shop.teknofibra.it/{{ $product->slug }}</x-slot:canonical>
    <div class="pt-10" x-data="productItem({{ json_encode([
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'image' => $product->image,
                    'title' => $product->title,
                    'price' => $product->price,
                    'quantity' => $product->quantity,
                    'addToCartUrl' => route('cart.add', $product)
                ]) }})" class="container mx-auto">
        <div class="grid gap-6 grid-cols-1 lg:grid-cols-5">
            <div class="lg:col-span-3 flex flex-col items-end gap-5">
                <div
                    class="bg-transparent w-full md:w-9/12"
                    x-data="{
                        images: {{$product->images->map(fn($im) => $im->url)}},
                        activeImage: null,
                        prev() {
                            let index = this.images.indexOf(this.activeImage);
                            if (index === 0)
                                index = this.images.length;
                            this.activeImage = this.images[index - 1];
                        },
                        next() {
                            let index = this.images.indexOf(this.activeImage);
                            if (index === this.images.length - 1)
                                index = -1;
                            this.activeImage = this.images[index + 1];
                        },
                        init() {
                            this.activeImage = this.images.length > 0 ? this.images[0] : null
                        }
                    }"
                >
                    <div class="relative">
                        {{-- @dd($product->images === 0) --}}
                        @if ($product->images && count($product->images) > 0)
                            <template x-for="image in images">
                                <div
                                    x-show="activeImage === image"
                                    class="aspect-w-3 aspect-h-2"
                                >
                                    <img :src="image" alt="{{ app()->getLocale() == 'en' ? $product->title_en : $product->title }}" class="w-auto mx-auto"/>
                                </div>
                            </template>
                        @else
                            <div
                            
                            class="aspect-w-3 aspect-h-2"
                            >
                            <img src="/img/noimage.png" alt="No Image Picture" class="w-auto mx-auto"/>
                            </div>
                        @endif
                        <a
                            @click.prevent="prev"
                            class="cursor-pointer bg-black/30 text-white absolute left-0 top-1/2 -translate-y-1/2"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-10 w-10"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 19l-7-7 7-7"
                                />
                            </svg>
                        </a>
                        <a
                            @click.prevent="next"
                            class="cursor-pointer bg-black/30 text-white absolute right-0 top-1/2 -translate-y-1/2"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-10 w-10"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </a>
                    </div>
                    <div class="flex">
                        <template x-for="image in images">
                            <a
                                @click.prevent="activeImage = image"
                                class="cursor-pointer w-[80px] h-[80px] border border-gray-300 hover:border-purple-500 flex items-center justify-center"
                                :class="{'border-purple-600': activeImage === image}"
                            >
                                <img :src="image" alt="" class="w-auto max-auto max-h-full"/>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-2 md:w-9/12">
                <h1 class="text-2xl font-semibold pb-5">
                    {{ app()->getLocale() == 'en' ? $product->title_en : $product->title }}
                </h1>
                <div class="border-t-2 border-gray-300"></div>
                <div class="mb-6">
                    <div
                        class="wysiwyg-content pt-5"
                    >
                        {!! app()->getLocale() == 'en' ? $product->description_en : $product->description !!}
                    </div>
                    
                </div>
                <div class="border-t-2 border-gray-300"></div>
                <p class="text-xl font-bold my-6">€ {{$product->price}}</p>
                @if ($product->quantity <= 0)
                    <div class="bg-red-500 text-white py-2 px-3 rounded mb-3 text-center">
                        {{ __('ui.out_of_stock') }}
                    </div>
                @endif
                <div class="mb-5">
                    <label for="quantity" class="block font-bold mr-4">
                        {{ __('cart.quantity') }}
                    </label>
                    <div class="flex items-end">
                        <input
                        :disabled="product.quantity <= 0"
                        type="number"
                        name="quantity"
                        x-ref="quantityEl"
                        value="1"
                        min="1"
                        class="w-32 focus:border-purple-500 focus:outline-none rounded text-black mr-3"
                        :class="product.quantity <= 0 ? 'bg-gray-300' : 'bg-white'"
                    />
                    <p>mt/pz.</p>
                    </div>
                </div>
                <button
                    :disabled="product.quantity <= 0"
                    @click="addToCart($refs.quantityEl.value)"
                    class="btn-primary py-4 text-lg flex justify-center min-w-0 w-full mb-6"
                    :class="product.quantity <= 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                    </svg>
                    {{ __('ui.add_to_cart') }}
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
