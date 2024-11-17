<x-app-layout>
    <x-slot:title>Teknofibra Shop | Support</x-slot:title>
    <x-slot:description>Contatta il nostro team di supporto tramite il modulo di contatto per ricevere assistenza rapida e dedicata. Compila la tua richiesta e ottieni risposte alle domande su ordini, spedizioni, resi o qualsiasi altra necessità. Siamo qui per aiutarti a risolvere ogni problema per una migliore esperienza di acquisto</x-slot:description>
    <x-slot:canonical>https://shop.teknofibra.it/support</x-slot:canonical>
    <section class="min-h-screen max-h-fit grid place-items-center">
        <h2 class="title hidden md:block">Support</h2>
        <h1 class="text-6xl font-bold">{{ __('support.title') }}</h1>
        <div class="max-w-7xl p-5 space-y-5">
            {!! __('support.text') !!}
        </div>
        <div class="md:w-8/12">
            @if (session('message'))
                <div class="p-3 rounded-lg bg-emerald-500 text-white text-center mb-5">
                    {{ session('message') }}
                </div>
            @endif

            <form class="p-5 bg-white/50 rounded-lg md::w-7xl space-y-5" action="{{ route('support.submit') }}" method="POST">
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
                    <input type="text" name="order_number" placeholder="{{ __('support.order_number') }}" class="rounded-lg" value="{{ old('order_number') }}" >
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