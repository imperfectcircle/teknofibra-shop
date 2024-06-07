<x-app-layout>
    <!-- Product List -->
    <div
    class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 p-5"
    >
    
    @foreach ($products as $product )
    <!-- Product Item -->
    <div x-data="productItem({{ json_encode([
        'id' => $product->id,
        'image' => $product->image,
        'title' => $product->title,
        'price' => $product->price,
        'quantity' => $product->quantity,
        'addToCartUrl' => route('cart.add', $product),
    ]) }})" class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 flex flex-col">
        <a href="{{ route('product.view', $product->slug) }}">
            <img class="rounded-t-lg" src="{{ $product->image }}" alt="{{ $product->title }}" />
        </a>
        <div class="p-5 flex-grow">
            <a href="{{ route('product.view', $product->slug) }}">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $product->title }}</h5>
            </a>
            <p class="mb-3 font-normal text-lg text-gray-700 dark:text-gray-400">€ {{ $product->price }}</p>
        </div>
        @if ($product->quantity <= 0)
                    <div class="bg-red-500 text-white py-2 px-3 rounded mb-3 text-center mx-5">
                        Questo prodotto al momento è esaurito.
                    </div>
                @endif
        <div class="p-5 pt-0 mt-auto"> 
            <button :disabled="product.quantity <= 0" @click="addToCart()" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-indigo-700 rounded-lg hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800" :class="product.quantity <= 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'">
                Aggiungi al Carrello
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
            </button>
        </div>
    </div>



        {{-- <div
            x-data="productItem({{ json_encode([
                'id' => $product->id,
                'image' => $product->image,
                'title' => $product->title,
                'price' => $product->price,
                'addToCartUrl' => route('cart.add', $product),
            ]) }})"
            class="border border-1 border-gray-200 rounded-md hover:border-purple-600 transition-colors bg-white"
        >
            <a href="{{ route('product.view', $product->slug) }}" class="block overflow-hidden aspect-w-3 aspect-h-2">
                <img
                    src="{{ $product->image }}"
                    alt=""
                    class="rounded-lg hover:scale-105 hover:rotate-1 transition-transform w-auto max-h-full mx-auto py-3"
                />
            </a>
            <div class="p-4">
                <h3 class="text-lg">
                    <a href="{{ route('product.view', $product->slug) }}">
                    {{ $product->title }}
                    </a>
                </h3>
                <h5 class="font-bold">€ {{ $product->price }}</h5>
            </div>
            <div class="flex justify-between py-3 px-4">
                <button class="btn-primary" @click="addToCart()">
                    Aggiungi al Carrello
                </button>
            </div>
        </div>
        <!--/ Product Item --> --}}
        @endforeach
    </div>
    <!--/ Product List -->
    {{ $products->links() }}
</x-app-layout>