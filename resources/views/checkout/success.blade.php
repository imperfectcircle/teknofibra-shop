<x-app-layout>
    <div class="w-full min-h-fit grid place-items-center bg-white pb-5">
        <img src="/img/check.png" alt="">
        <p class="text-4xl text-emerald-600">Grazie {{ $customer->name }},</p>
        <p class="text-2xl text-emerald-600">Il tuo ordine è andato a buon fine!</p>
        <p class="text-xl text-emerald-600">A breve riceverai un'email con il riepilogo del tuo ordine.</p>
        <div class="flex py-5 space-x-3">
            <a class="px-6 py-2 rounded shadow border border-emerald-600 hover:bg-emerald-500 hover:text-white transition-colors" href="{{ route('home') }}">Torna alla Home</a>
            <a class="px-6 py-2 rounded shadow border border-emerald-600 hover:bg-emerald-500 hover:text-white transition-colors" href="{{ route('orders.index') }}">Vai ai tuoi Ordini</a>
        </div>
    </div>
</x-app-layout>