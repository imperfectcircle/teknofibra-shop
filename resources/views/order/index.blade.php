<?php
/** @var \Illuminate\Database\Eloquent\Collection $orders */
?>

<x-app-layout>
    <x-slot:title>Teknofibra Shop | Orders</x-slot:title>
    <x-slot:description>Consulta il riepilogo dei tuoi ordini in un'unica pagina. Visualizza dettagli su ogni acquisto, verifica lo stato di spedizione e accedi a informazioni aggiornate per ogni ordine. Gestisci comodamente il tuo storico acquisti per un’esperienza di shopping sempre sotto controllo e senza sorprese.</x-slot:description>
    <x-slot:canonical>https://shop.teknofibra.it/orders</x-slot:canonical>
    <h2 class="title">Orders</h2>
    <div class="container mx-auto lg:w-2/3 p-5">
        <h1 class="text-3xl font-bold mb-2">{{ __('orders.my_orders') }}</h1>
        <div class="bg-white rounded-lg p-3 overflow-x-auto">
            <div class="overflow-x-scroll md:overflow-hidden">
                <table class="table-auto w-full">
                    <thead>
                    <tr class="border-b-2 text-black">
                        <th class="text-left p-2">{{ __('orders.order_number') }}</th>
                        <th class="text-left p-2">{{ __('orders.order_date') }}</th>
                        <th class="text-left p-2">{{ __('orders.order_status') }}</th>
                        <th class="text-left p-2">{{ __('orders.order_subtotal') }}</th>
                        <th class="text-left p-2">{{ __('orders.order_shipping_costs') }}</th>
                        <th class="text-left p-2">{{ __('orders.order_items') }}</th>
                        <th class="text-left p-2">{{ __('orders.order_actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b text-black">
                            <td class="py-1 px-2">
                                <a
                                    href="{{ route('order.view', $order) }}"
                                    class="text-purple-600 hover:text-purple-500"
                                >
                                    #{{$order->id}}
                                </a>
                            </td>
                            <td class="py-1 px-2 whitespace-nowrap">{{$order->created_at}}</td>
                            <td class="py-1 px-2">
                                <small class="text-white py-1 px-2 rounded
                                    {{$order->isPaid() ? 'bg-emerald-500' : 'bg-gray-400' }}">
                                    {{$order->status}}
                                </small
                                >
                            </td>
                            <td class="py-1 px-2">€ {{$order->total_price}}</td>
                            <td class="py-1 px-2">€ {{$order->shipping_cost}}</td>
                            <td class="py-1 px-2 whitespace-nowrap">{{$order->items_count}} {{ __('orders.order_items') }}</td>
                            <td class="py-1 px-2 flex gap-2 w-[100px]">
                                @if (!$order->isPaid())
                                    <form action="{{ route('cart.checkout-order', $order) }}"
                                            method="POST">
                                        @csrf
                                        <button
                                            class="flex items-center py-1 btn-primary whitespace-nowrap"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="2"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                                />
                                            </svg>
                                            {{ __('orders.pay') }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3 bg-white rounded px-2 py-1">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>