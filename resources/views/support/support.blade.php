<x-app-layout>
    <section class="min-h-screen max-h-fit grid place-items-center">
        <h2 class="title">Support</h2>
        <h1 class="text-6xl font-bold">{{ __('support.title') }}</h1>
        <div class="w-8/12">
            @if (session('message'))
                <div class="p-3 rounded-lg bg-emerald-500 text-white text-center mb-5">
                    {{ session('message') }}
                </div>
            @endif

            <form class="p-5 bg-white/50 rounded-lg w-7xl space-y-5" action="{{ route('support.submit') }}" method="POST">
                @csrf
                <label class="flex flex-col text-black">
                    {{ __('support.name') }}
                    <input type="text" name="name" placeholder="{{ __('support.name_ph') }}" class="rounded-lg" value="{{ old('name') }}" required>
                </label>
                <label class="flex flex-col text-black">
                    {{ __('support.email') }}
                    <input type="email" name="email" placeholder="{{ __('support.email_ph') }}" class="rounded-lg" value="{{ old('email') }}" required>
                </label>
                <label class="flex flex-col text-black">
                    {{ __('support.message') }}
                    <textarea name="message" class="rounded-lg resize-none" cols="30" rows="10" required>{{ old('message') }}</textarea>
                </label>
                <div class="flex items-center">
                    <input class="mr-1" type="checkbox" name="privacy" required>
                    <p class="text-black">{!! __('support.privacy') !!}</p>
                </div>
                <button type="submit" class="btn-primary w-full">{{ __('support.submit') }}</button>
            </form>
        </div>
    </section>
</x-app-layout>