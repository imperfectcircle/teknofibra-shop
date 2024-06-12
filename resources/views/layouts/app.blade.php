<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-slate-300 overflow-y-scroll" >
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Teknofibra Shop') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
      [x-cloak] {
        display: none !important;
      }
    </style>
  </head>
  <body>
    @include('layouts.navigation')

    <main class="p-5 mt-[81px] md:mt-[94px]">
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
    <div id="scrollTop" class="scrollTop fixed bottom-5 right-5 md:bottom-10 md:right-10 rounded-full opacity-0 bg-white p-2 shadow transition-all duration-200" style="--clip-path: inset(0% 100% 0% 0%);">
      <lord-icon
          src="https://cdn.lordicon.com/dxnllioo.json"
          trigger="hover"
          class="w-[40px] h-[40px]"
      >
      </lord-icon>
  </div>
  <x-footer />
  </body>
</html>
