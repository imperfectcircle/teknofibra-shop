<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>Buongiorno, hai ricevuto un messaggio da {{$contact['name']}}</h2>
    <p>{{ $contact['email'] }}</p>
    <p>Questo è il messaggio:</p>
    <p>{{$contact['message']}}</p>

</body>
