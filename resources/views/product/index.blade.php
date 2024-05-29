<x-app-layout>
    <!-- Product List -->
    <div
    class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 p-5"
    >
    
    @foreach ($products as $product )
    <!-- Product Item -->
        <div
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
        <!--/ Product Item -->
        @endforeach
    </div>
    <!--/ Product List -->
    {{ $products->links() }}
</x-app-layout>