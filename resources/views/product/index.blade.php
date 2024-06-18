<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
$categoryList = \App\Models\Category::getActiveAsTree();

?>

<x-app-layout>

    {{-- <div class="hero w-full grid place-items-center -m-5">
        

    </div> --}}
    
    <div id="controls-carousel" class="relative w-full" data-carousel="static">
        <!-- Carousel wrapper -->
        <div class="relative h-[50vh] overflow-hidden md:min-h-[65vh] bg-center bg-no-repeat bg-cover bg-custom-gradient-image -mt-5 -mb-5 -ml-5 -mr-5 grid place-items-center">
            <!-- Item 1 -->
            <h1 class="my-10 text-center text-xl md:text-3xl lg:text-4xl font-semibold text-white">Scopri le Soluzioni Isolanti di TeKnofibra: <br> Acquista Ora i Migliori Prodotti per l'Automotive e l'Aviazione!</h1>
        </div>
        
        
    </div>
    
    <x-category-list :category-list="$categoryList" class="-ml-5 mt-5 -mr-5 px-4"/>

    <!-- Search & Sort -->
    
    <div class="flex flex-col md:hidden gap-2 items-center p-3 pb-0 my-5" x-data="{
        selectedSort: '{{ request()->get('sort', '-updated_at') }}',
        searchKeyword: '{{ request()->get('search') }}',
        updateUrl() {
            const params = new URLSearchParams(window.location.search)
            if (this.selectedSort && this.selectedSort !== '-updated_at') {
                params.set('sort', this.selectedSort)
            } else {
                params.delete('sort')
            }

            if (this.searchKeyword) {
                params.set('search', this.searchKeyword)
            } else {
                params.delete('search')
            }
            window.location.href = window.location.origin + window.location.pathname + '?'
            + params.toString();
        }
    }">
        <form action="" method="GET" class="flex-1" @submit.prevent="updateUrl">
            <x-text-input class="text-black" type="text" name="search" placeholder="Ricerca Prodotti"
                    x-model="searchKeyword"/>
        </form>
        <x-text-input
            x-model="selectedSort"
            @change="updateUrl"
            type="select"
            name="sort"
            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded text-black">
            <option value="price">Prezzo (ASC)</option>
            <option value="-price">Prezzo (DESC)</option>
            <option value="title">Titolo (ASC)</option>
            <option value="-title">Titolo (DESC)</option>
            <option value="-updated_at">Ultimi Aggiornati in alto</option>
            <option value="updated_at">Ultimi Aggiornati in basso</option>
        </x-text-input>
    </div>
    
  
  <!--/ Search & Sort -->
    
    <?php if ( $products->count() === 0 ): ?>
    <div class="text-center text-gray-600 py-16 text-xl">
        Non sono presenti Articoli
    </div>
    <?php else: ?>

    <!-- Product List -->
    <div
    class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 p-5 pt-10 place-items-center"
    >
    
        @foreach ($products as $product )
        <!-- Product Item -->
            <div x-data="productItem({{ json_encode([
                'id' => $product->id,
                'image' => $product->image ?: '/img/noimage.png',
                'title' => $product->title,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'addToCartUrl' => route('cart.add', $product),
                ]) }})" class="max-w-[20rem] min-h-full relative card">
                <a href="{{ route('product.view', $product->slug) }}">
                    
                        @if ($product->quantity <= 0)
                        <div class="bg-red-500 text-white py-2 px-3 rounded mb-3 text-center mx-5 absolute top-[88px]">
                            Questo prodotto al momento è esaurito.
                        </div>
                        @endif
                        <div class="relative rounded-lg bg-white w-[320px] max-h-[240px] flex items-center justify-center">
                            <img class="rounded-lg max-h-[240px] w-[320px] object-contain" src="{{ $product->image ?: '/img/noimage.png' }}" alt="{{ $product->title }}" />
                        </div>
                    
                </a>
                <div class="card-content p-5 text-white">
                    <a href="{{ route('product.view', $product->slug) }}">
                        <h5 class="mb-2 text-lg font-bold tracking-tight">{{ Str::limit($product->title, 100) }}</h5>
                    </a>
                    <p class="mb-3 font-normal text-lg">€ {{ $product->price }}</p>
                    
                <div class="relative z-50 p-5 pt-0 mt-auto"> 
                    <button :disabled="product.quantity <= 0" @click="addToCart()" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-indigo-700 rounded-lg hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800" :class="product.quantity <= 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'">
                        Aggiungi al Carrello
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endforeach
    </div>
    <!--/ Product List -->
    {{-- {{$products->appends(['sort' => request('sort'), 'search' => request('search')])->links()}} --}}
    <?php endif; ?>
    
</x-app-layout>