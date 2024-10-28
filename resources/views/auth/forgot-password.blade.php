<x-app-layout>
  <x-slot:title>Teknofibra Shop | Forgot Password</x-slot:title>
  <x-slot:description></x-slot:description>
  <x-slot:canonical>https://shop.teknofibra.it/forgot-password</x-slot:canonical>
    <form action="{{ route('password.email') }}" method="post" class="w-[400px] mx-auto p-6 my-16">
        @csrf
        <h2 class="text-2xl font-semibold text-center mb-5">
            {{ __('forgot.enter_email') }}
        </h2>
        <p class="text-center text-sm text-white mb-6">
          {{ __('register.or') }}
          <a
            href="{{ route('login') }}"
            class="text-white text-sm underline hover:text-gray-300 transition-color"
            >{{ __('register.login_to_account') }}</a
          >
        </p>

        <x-auth-session-status class="mb-4 text-white" :status="session('status')" />

        <div class="mb-3">
          <x-text-input
            id="loginEmail"
            type="email"
            name="email"
            :value="old('email')"
            required
            autofocus
            placeholder="{{ __('forgot.email') }}"
          />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <button
          class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 w-full"
        >
        {{ __('forgot.send_password_reset_link') }}
        </button>
      </form>
</x-app-layout>
