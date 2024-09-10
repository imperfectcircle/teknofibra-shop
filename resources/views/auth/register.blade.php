<x-app-layout>
    <form
        action="{{ route('register') }}"
        method="post"
        class="max-w-[400px] mx-auto p-6 my-16"
      >
      @csrf
        <h2 class="text-2xl font-semibold text-center mb-4">{{ __('register.create_new_account') }}</h2>
        <p class="text-center text-white mb-3">
          {{ __('register.or') }}
          <a
            href="{{ route('login') }}"
            class="text-sm text-white hover:text-gray-300 transition-color underline"
            >{{ __('register.login_to_account') }}</a
          >
        </p>

        @if (session('error'))
          <div class="py-2 px-3 bg-red-500 text-white mb-3 rounded text-center">
            {{ session('error') }}
          </div>
        @endif
        <div class="mb-4">
          <x-text-input
            placeholder="{{ __('register.name') }}"
            type="text"
            name="name"
            :value="old('name')"
          />
          <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        </p>
        <div class="mb-4">
          <x-text-input
            placeholder="{{ __('register.email') }}"
            type="email"
            name="email"
            :value="old('email')"
          />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mb-4">
          <x-text-input
            placeholder="{{ __('register.password') }}"
            type="password"
            name="password"
          />
          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        </div>
        <div class="mb-4">
          <x-text-input
            placeholder="{{ __('register.confirm_password') }}"
            type="password"
            name="password_confirmation"
          />
          <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button
          class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 w-full"
        >
        {{ __('register.register') }}
        </button>
      </form>
</x-guest-layout>
