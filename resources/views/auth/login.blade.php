<x-app-layout>
    <form action="{{ route('login') }}" method="post" class="w-[400px] mx-auto p-6 my-16">
        @csrf
        <h2 class="text-2xl font-semibold text-center mb-5">
          Accedi al tuo account
        </h2>
        <p class="text-center text-white mb-6">
          oppure
          <a
            href="{{ route('register') }}"
            class="text-sm text-white underline hover:text-gray-300 transition-color"
            >Crea un nuovo account</a
          >
        </p>
        <div class="mb-4">
          <x-text-input
            id="loginEmail"
            type="email"
            name="email"
            :value="old('email')"
            placeholder="Il tuo indirizzo email"
          />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mb-4">
          <x-text-input
            id="loginPassword"
            type="password"
            name="password"
            placeholder="La tua password"
          />
          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-between items-center mb-5">
          <div class="flex items-center">
            <input
              id="loginRememberMe"
              name="remember"
              type="checkbox"
              class="mr-3 rounded border-gray-300 text-purple-500 focus:ring-purple-500"
            />
            <label for="loginRememberMe">Ricordami</label>
          </div>
          <a href="{{ route('password.request') }}" class="text-sm text-white hover:text-gray-300 underline transition-colors">Password Dimenticata?</a>
        </div>
        <button
          class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 w-full"
        >
          Accedi
        </button>
      </form>
</x-app-layout>
