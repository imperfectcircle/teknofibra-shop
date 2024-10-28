<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-slate-300 overflow-y-scroll" >
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $description }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:locale" content="it_IT">
    <meta property="og:site_name" content="">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="">
    <meta name="twitter:title" content="">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="">
    <link rel="canonical" href="{{ $canonical }}">
    <title>{{ $title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Scripts -->
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (request()->session()->get('locale') === 'en')
        <script type="text/javascript">
            var _iub = _iub || [];
            _iub.csConfiguration = {"siteId":3784824,"cookiePolicyId":72210443,"lang":"en","storage":{"useSiteId":true}};
            </script>
            <script type="text/javascript" src="https://cs.iubenda.com/autoblocking/3784824.js"></script>
            <script type="text/javascript" src="//cdn.iubenda.com/cs/gpp/stub.js"></script>
            <script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>
        @else
        <script type="text/javascript">
            var _iub = _iub || [];
            _iub.csConfiguration = {"siteId":3784824,"cookiePolicyId":82606974,"lang":"it","storage":{"useSiteId":true}};
            </script>
            <script type="text/javascript" src="https://cs.iubenda.com/autoblocking/3784824.js"></script>
            <script type="text/javascript" src="//cdn.iubenda.com/cs/gpp/stub.js"></script>
            <script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>
        @endif
    

    <style>
      [x-cloak] {
        display: none !important;
      }
    </style>
  </head>
  <body>
    @include('layouts.navigation')

    <main class="p-5 mt-[81px] products">
      {{ $slot }}
    </main>

    <!-- Toast -->
    <div
      x-data="toast"
      x-show="visible"
      x-transition
      x-cloak
      @notify.window="show($event.detail.message, $event.detail.type || 'success')"
      class="fixed z-50 w-[400px] left-1/2 -ml-[200px] top-16 py-2 px-4 pb-4 text-white"
      :class="type === 'success' ? 'bg-emerald-500' : 'bg-red-500'"
    >
      <div class="font-semibold" x-text="message"></div>
      <button
        @click="close"
        class="absolute flex items-center justify-center right-2 top-2 w-[30px] h-[30px] rounded-full hover:bg-black/10 transition-colors"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </button>
      <!-- Progress -->
      <div>
        <div
          class="absolute left-0 bottom-0 right-0 h-[6px] bg-black/10"
          :style="{'width': `${percent}%`}"
        ></div>
      </div>
    </div>
    <!--/ Toast -->

    {{-- Scroll to top --}}
    <div id="scrollTop" class="scrollTop z-20 fixed bottom-5 right-5 md:bottom-10 md:right-10 rounded-full opacity-0 bg-white p-2 shadow transition-all duration-200" style="--clip-path: inset(0% 100% 0% 0%);">
      <lord-icon
          src="https://cdn.lordicon.com/dxnllioo.json"
          trigger="hover"
          class="w-[30px] h-[30px]"
      >
      </lord-icon>
  </div>

  @if (!Route::is('cart.index'))
    <div class="z-20 fixed bottom-20 right-5 md:bottom-32 md:right-10 rounded-full bg-gray-200 p-2 shadow transition-all duration-200" x-data="{
      mobileMenuOpen: false,
      cartItemsCount: {{ \App\Http\Helpers\CartHelper::getCartItemsCount() }},
    }"
    @cart-change.window="cartItemsCount = $event.detail.count"
    id="header"
    class="fixed top-0 left-0 z-50 w-full flex justify-between items-center shadow-md text-white">
      <a
        href="{{ route('cart.index') }}"
        class="relative flex items-center justify-between py-2 px-3"
      >
        <div class="flex items-center">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 mr-2 -mt-1"
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
          
        </div>
        <!-- Cart Items Counter -->
        <p
          x-show="cartItemsCount"
          x-transition
          x-text="cartItemsCount"
          class="py-[2px] px-[8px] rounded-full bg-emerald-500 text-white font-semibold"
        ></p>
        <!--/ Cart Items Counter -->
      </a>
    </div>
  @endif
  <x-footer />
  
  </body>
</html>
