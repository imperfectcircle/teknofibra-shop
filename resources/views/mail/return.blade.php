<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>Buongiorno, hai ricevuto una richiesta di reso da {{$contact['name']}}</h2>
    <p>{{ $contact['email'] }}</p>
    <p>Numero Ordine: {{ $contact['order_number'] }}</p>
    <p>Data di acquisto: {{ $contact['order_date'] }}</p>
    <p>Data di ricevimento: {{ $contact['received_date'] }}</p>
    <p>Descrizione prodotto:</p>
    <p>{{$contact['description']}}</p>

</body>
