<x-app-layout>
    <x-slot:title>Teknofibra Shop | Return Policy</x-slot:title>
    <x-slot:description>Scopri la nostra politica di reso semplice e trasparente. Resi e rimborsi rapidi per garantire la tua soddisfazione. Leggi i dettagli su come effettuare un reso in pochi passi.</x-slot:description>
    <x-slot:canonical>https://shop.teknofibra.it/return-policy</x-slot:canonical>
    <section class="min-h-screen max-h-fit grid place-items-center">
        <h2 class="title hidden md:block">Return</h2>
        <h1 class="text-6xl font-bold">{{ __('return.title') }}</h1>
        <div class="max-w-7xl p-5 space-y-5">
            {!! __('return.text') !!}
        </div>
        <div class="md:w-8/12">
            @if (session('message'))
                <div class="p-3 rounded-lg bg-emerald-500 text-white text-center mb-5">
                    {{ session('message') }}
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="p-3 rounded-lg bg-red-500 text-white text-center mb-5">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form class="p-5 bg-white/50 rounded-lg md::w-7xl space-y-5" action="{{ route('return.submit') }}" method="POST">
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
                    {{ __('support.order_number') }}
                    <input type="text" name="order_number" placeholder="{{ __('support.order_number') }}" class="rounded-lg" value="{{ old('order_number') }}" required>
                </label>
                <div class="flex space-x-5">
                    <label class="flex flex-col text-black">
                        {{ __('return.order_date') }}
                        <input type="date" name="order_date"  class="rounded-lg" required>
                    </label>
                    <label class="flex flex-col text-black">
                        {{ __('return.order_received') }}
                        <input type="date" name="received_date"  class="rounded-lg" required>
                    </label>
                </div>
                <label class="flex flex-col text-black">
                    {{ __('return.description') }}
                    <textarea name="description" class="rounded-lg resize-none" cols="30" rows="10" required>{{ old('description') }}</textarea>
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