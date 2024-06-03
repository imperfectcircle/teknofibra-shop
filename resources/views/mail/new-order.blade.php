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
        <th>Stato dell'Ordine</th>
        <td>{{ $order->status }}</td>
    </tr>
    <tr>
        <th>Prezzo dell'Ordine</th>
        <td>€ {{$order->total_price}}</td>
    </tr>
    <tr>
        <th>Data Ordine</th>
        <td>{{$order->created_at}}</td>
    </tr>
</table>
<table>
    <tr>
        <th>Imagine</th>
        <th>Titolo</th>
        <th>Prezzo</th>
        <th>Quantità</th>
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