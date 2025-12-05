<!DOCTYPE html>
<html>
<head><title>Clima - {{ $city->name }}</title></head>
<body>
    <h1>Clima actual en {{ $city->name }}</h1>
    <a href="{{ route('cities.index') }}">← Volver a la lista</a>
    <hr>

    @if(isset($weather['weather']))
        <h2>{{ ucfirst($weather['weather'][0]['description']) }}</h2>
        <p><strong>Temperatura:</strong> {{ $weather['main']['temp'] }}°C</p>
        <p><strong>Sensación térmica:</strong> {{ $weather['main']['feels_like'] }}°C</p>
        <p><strong>Humedad:</strong> {{ $weather['main']['humidity'] }}%</p>
        <p><strong>Viento:</strong> {{ $weather['wind']['speed'] }} m/s</p>
        <p><strong>Presión:</strong> {{ $weather['main']['pressure'] }} hPa</p>

        <img src="https://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@2x.png" alt="icon">

        @if($city->image)
            <br><br>
            <img src="{{ asset('storage/'.$city->image) }}" width="300">
        @endif
    @else
        <p style="color:red">No se pudo obtener el clima. Verifica la API key o coordenadas.</p>
    @endif
</body>
</html>