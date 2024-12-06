<h1>
    È Stato creato un nuovo ordine
</h1>

<table>
    <tr>
        <th>ID dell'Ordine</th>
        <td>
            <a href="{{ $forAdmin ? env('BACKEND_URL').'/app/orders/'.$order->id : route('order.view', $order, true) }}">
                {{$order->id}}
            </a>
        </td>
    </tr>
    <tr>
        <th>Order Status</th>
        <td>{{ $order->status }}</td>
    </tr>
    <tr>
        <th>Total Price</th>
        <td>€ {{$order->total_price}}</td>
    </tr>
    <tr>
        <th>Order Date</th>
        <td>{{$order->created_at}}</td>
    </tr>
</table>
<table>
    <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Price</th>
        <th>Quantity</th>
    </tr>
    @foreach($order->items as $item)
        <tr>
            <td>
                <img src="{{$item->product->image}}" style="width: 100px">
            </td>
            <td>{{$item->product->title}}</td>
            <td>€ {{$item->unit_price * $item->quantity}}</td>
            <td>{{$item->quantity}}</td>
        </tr>
    @endforeach
</table>
<div>
    <h3>Nome e Cognome</h3>
    <p>{{ $name }}</p>
</div>
<div>
    <h3>Indirizzo di Fatturazione:</h3>
    <p>{{ $billingAddress->address1 }}</p>
    <p>{{ $billingAddress->address2 }}</p>
    <p>{{ $billingAddress->city }}, {{ $billingAddress->state }} {{ $billingAddress->zipcode }}</p>
    <p>{{ $billingAddress->country }}</p>
</div>

<!-- Indirizzo di spedizione esistente -->
<div>
    <h3>Indirizzo di Spedizione:</h3>
    <p>{{ $shippingAddress->address1 }}</p>
    <p>{{ $shippingAddress->address2 }}</p>
    <p>{{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->zipcode }}</p>
    <p>{{ $shippingAddress->country }}</p>
</div>