<x-app-layout>
    <x-slot:title>Teknofibra Shop | Order Details</x-slot:title>
    <x-slot:description>Esamina i dettagli completi del tuo ordine, inclusi i prodotti acquistati, i prezzi, lo stato di spedizione e le informazioni di consegna. Accedi a tutti i dati necessari per monitorare il progresso del tuo ordine e gestire eventuali richieste di assistenza. Mantieni il controllo sui tuoi acquisti con una panoramica chiara e aggiornata per ogni transazione.</x-slot:description>
    <x-slot:canonical>https://shop.teknofibra.it/orders/views/{{ $order->id }}</x-slot:canonical>
    <div class="container mx-auto lg:w-2/3 p-5">
        <h1 class="text-3xl font-bold mb-2">{{ __('orders.order_number') }} {{$order->id}}</h1>
        <div class="bg-white rounded-lg p-3">
            <table class="text-black">
                <tbody>
                <tr>
                    <td class="font-bold py-1 px-2">{{ __('orders.order_number') }}</td>
                    <td>{{$order->id}}</td>
                </tr>
                <tr>
                    <td class="font-bold py-1 px-2">{{ __('orders.order_date') }}</td>
                    <td>{{$order->created_at}}</td>
                </tr>
                <tr>
                    <td class="font-bold py-1 px-2">{{ __('orders.order_status') }}</td>
                    <td>
                        <span
                            class="text-white py-1 px-2 rounded {{$order->isPaid() ? 'bg-emerald-500' : 'bg-gray-400'}}">
                            {{$order->status}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="font-bold py-1 px-2">{{ __('cart.shipping_costs') }}</td>
                    <td>€ {{ $order->shipping_cost }}</td>
                </tr>
                @if($order->discount > 0)
                <tr>
                    <td class="font-bold py-1 px-2">{{ __('cart.discount') }}</td>
                    <td>- € {{ $order->discount }}</td>
                </tr>
                @endif
                <tr>
                    <td class="font-bold py-1 px-2">{{ __('cart.total') }}</td>
                    <td>€ {{ $order->total_price }}</td>
                </tr>
                
                {{-- <tr>
                    <td class="font-bold py-1 px-2">{{ __('cart.total') }}</td>
                    <td>€ {{ $order->shipping_cost + $order->total_price }}</td>
                </tr> --}}
                </tbody>
            </table>

            <hr class="my-5"/>

            @foreach($order->items()->with('product')->get() as $item)
                <!-- Order Item -->
                <div class="flex flex-col sm:flex-row items-center  gap-4 text-black">
                    <a href="{{ route('product.view', $item->product) }}"
                        class="w-36 h-32 flex items-center justify-center overflow-hidden">
                        <img src="{{$item->product->image}}" class="object-cover" alt=""/>
                    </a>
                    <div class="flex flex-col justify-between">
                        <div class="flex justify-between mb-3">
                            <h3>
                                {{ app()->getLocale() == 'en' ? $item->product->title_en : $item->product->title }}
                            </h3>
                        </div>
                        <div class="flex justify-between items-center space-x-3">
                            <div class="flex items-center "><p>{{ __('orders.order_quantity') }}: {{$item->quantity}}</p></div>
                            <div class="text-lg font-semibold"> € {{$item->unit_price}} </div>
                        </div>
                    </div>
                </div>
                <!--/ Order Item -->
                <hr class="my-3"/>
            @endforeach

            @if (!$order->isPaid())
                <form action="{{ route('cart.checkout-order', $order) }}"
                    method="POST">
                    @csrf
                    <button class="btn-primary flex items-center justify-center w-full mt-3">
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
                        {{ __('orders.make_payment') }}
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>