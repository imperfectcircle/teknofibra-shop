<x-app-layout>
    <x-slot:title>Teknofibra Shop | The payment was unsuccessful</x-slot:title>
    <x-slot:description></x-slot:description>
    <x-slot:canonical></x-slot:canonical>
    <div class="w-[400px] mx-auto bg-red-500 py-2 px-3 text-white rounded">
        <h1>Il Pagamento non è andato a buon fine!</h1>
        <p>{{ $message ?? ''}}</p>
    </div>
</x-app-layout>