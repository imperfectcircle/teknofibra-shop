<x-app-layout>
  <x-slot:title>Teknofibra Shop | Login</x-slot:title>
  <x-slot:description>Accedi per effettuare i tuoi acquisti</x-slot:description>
  <x-slot:canonical>https://shop.teknofibra.it/login</x-slot:canonical>
    <form action="{{ route('login') }}" method="post" class="max-w-[400px] mx-auto p-6 my-16">
        @csrf
        <h2 class="text-2xl font-semibold text-center mb-5">
          {{ __('login.login_to_account') }}
        </h2>
        <p class="text-center text-white mb-6">
          {{ __('login.or') }}
          <a
            href="{{ route('register') }}"
            class="text-sm text-white underline hover:text-gray-300 transition-color"
            >{{ __('login.create_new_account') }}</a
          >
        </p>
        <div class="mb-4">
          <x-text-input
            id="loginEmail"
            type="email"
            name="email"
            :value="old('email')"
            placeholder="{{ __('login.email') }}"
          />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mb-4">
          <x-text-input
            id="loginPassword"
            type="password"
            name="password"
            placeholder="{{ __('login.password') }}"
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
            <label for="loginRememberMe">{{ __('login.remember_me') }}</label>
          </div>
          <a href="{{ route('password.request') }}" class="text-sm text-white hover:text-gray-300 underline transition-colors">{{ __('login.forgot_password') }}</a>
        </div>
        <button
          class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 w-full"
        >
        {{ __('login.login') }}
        </button>
      </form>
</x-app-layout>
