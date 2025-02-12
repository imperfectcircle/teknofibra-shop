<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
$categoryList = \App\Models\Category::getActiveAsTree();

?>

<x-app-layout>
    <x-slot:title>Teknofibra Shop</x-slot:title>
    <x-slot:description>Ci sono materiali diversi, ma Teknofibra si distingue. Teknofibra scelta da chi vuole il meglio per i propri progetti.</x-slot:description>
    <x-slot:canonical>https://shop.teknofibra.it</x-slot:canonical>
    <div id="controls-carousel" class="relative w-full" data-carousel="static">
        <!-- Carousel wrapper -->
        <div class="relative h-[50vh] overflow-hidden md:min-h-[65vh] bg-center bg-no-repeat bg-cover bg-custom-gradient-image -mt-5 -mb-5 -ml-5 -mr-5 grid place-items-center z-10">
            <!-- Item 1 -->
            <h1 class="my-10 text-center text-xl md:text-3xl lg:text-4xl font-semibold text-white">{!! __('ui.header') !!}</h1>
        </div>
        
        
    </div>
    
    <x-category-list :category-list="$categoryList" class="-ml-5 mt-5 -mr-5 px-4 z-10 relative"/>

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
            <x-text-input class="text-black" type="text" name="search" placeholder="{{ __('ui.search') }}"
                    x-model="searchKeyword"/>
        </form>
        <x-text-input
            x-model="selectedSort"
            @change="updateUrl"
            type="select"
            name="sort"
            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded text-black">
            <option value="price">{{ __('ui.price') }}</option>
            <option value="-price">{{ __('ui.-price') }}</option>
            <option value="title">{{ __('ui.name') }}</option>
            <option value="-title">{{ __('ui.-name') }}</option>
            <option value="-updated_at">{{ __('ui.date') }}</option>
            <option value="updated_at">{{ __('ui.-date') }}</option>
        </x-text-input>
    </div>
    
  
  <!--/ Search & Sort -->
    
    <?php if ( $products->count() === 0 ): ?>
    <div class="text-center text-white py-16 text-xl">
        {{ __('ui.noitems') }}
    </div>
    <?php else: ?>

    <!-- Product List -->
    <div class="products -ml-5 -mr-5 -mb-5 -mt-5 sm:mt-0">
        <h2 class="title">Shop</h2>
        <div
        class="page grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 p-5 pt-10 place-items-center"
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
                                {{ __('ui.out_of_stock') }}
                            </div>
                            @endif
                            <div class="relative rounded-lg bg-white w-[320px] max-h-[240px] flex items-center justify-center">
                                <img class="rounded-lg max-h-[240px] w-[320px] object-contain" src="{{ $product->image ?: '/img/noimage.png' }}" alt="{{ app()->getLocale() == 'en' ? $product->title_en : $product->title }}" />
                            </div>
                        
                    </a>
                    <div class="card-content p-5 text-white relative z-0">
                        <a href="{{ route('product.view', $product->slug) }}">
                            <h5 class="mb-2 text-lg font-bold tracking-tight">{{ app()->getLocale() == 'en' ? Str::limit($product->title_en, 100) : Str::limit($product->title, 100) }}</h5>
                        </a>
                        <p class="mb-3 font-normal text-lg">€ {{ $product->price }}</p>
                        
                    <div class="relative z-50 p-5 pt-0 mt-auto"> 
                        <button :disabled="product.quantity <= 0" @click="addToCart()" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-indigo-700 rounded-lg hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800" :class="product.quantity <= 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'">
                            {{ __('ui.add_to_cart') }}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        
        </div>
    </div>
    <!--/ Product List -->
    {{-- {{$products->appends(['sort' => request('sort'), 'search' => request('search')])->links()}} --}}
    <?php endif; ?>
    
</x-app-layout>