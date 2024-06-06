<x-app-layout>
    <form
        action="{{ route('register') }}"
        method="post"
        class="w-[400px] mx-auto p-6 my-16"
      >
      @csrf
        <h2 class="text-2xl font-semibold text-center mb-4">Crea un nuovo account</h2>
        <p class="text-center text-gray-500 mb-3">
          o
          <a
            href="{{ route('login') }}"
            class="text-sm text-purple-700 hover:text-purple-600"
            >Accedi con un account esistente</a
          >
        </p>

        @if (session('error'))
          <div class="py-2 px-3 bg-red-500 text-white mb-3 rounded text-center">
            {{ session('error') }}
          </div>
        @endif
        <div class="mb-4">
          <x-text-input
            placeholder="Nome e Cognome"
            type="text"
            name="name"
            :value="old('name')"
          />
          <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        </p>
        <div class="mb-4">
          <x-text-input
            placeholder="Il tuo indirizzo email"
            type="email"
            name="email"
            :value="old('email')"
          />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mb-4">
          <x-text-input
            placeholder="Password"
            type="password"
            name="password"
          />
          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        </div>
        <div class="mb-4">
          <x-text-input
            placeholder="Ripeti la password"
            type="password"
            name="password_confirmation"
          />
          <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button
          class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 w-full"
        >
          Registrati
        </button>
      </form>
</x-guest-layout>
