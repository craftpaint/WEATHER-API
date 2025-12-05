@extends('layouts.app')
@section('title', 'Clima en '.$city->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white text-center py-4">
                <h2 class="mb-0">Clima actual en {{ $city->name }}</h2>
            </div>

            @if(isset($weather['weather']))
                <div class="card-body text-center py-5">
                    <img src="https://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@4x.png"
                         class="weather-icon" style="width: 200px;">

                    <h1 class="display-1 fw-bold text-primary my-4">
                        {{ round($weather['main']['temp']) }}°C
                    </h1>

                    <h4 class="text-capitalize mb-4">{{ $weather['weather'][0]['description'] }}</h4>

                    <div class="row text-center mt-5">
                        <div class="col">
                            <strong>Sensación</strong><br>
                            {{ round($weather['main']['feels_like']) }}°C
                        </div>
                        <div class="col">
                            <strong>Humedad</strong><br>
                            {{ $weather['main']['humidity'] }}%
                        </div>
                        <div class="col">
                            <strong>Viento</strong><br>
                            {{ $weather['wind']['speed'] }} m/s
                        </div>
                        <div class="col">
                            <strong>Presión</strong><br>
                            {{ $weather['main']['pressure'] }} hPa
                        </div>
                    </div>

                    @if($city->image)
                        <hr class="my-5">
                        <img src="{{ asset('storage/'.$city->image) }}" class="img-fluid rounded shadow" style="max-height: 400px;">
                    @endif
                </div>
            @else
                <div class="card-body text-center text-danger">
                    <h3>No se pudo obtener el clima</h3>
                    <p>Verifica tu API key o las coordenadas</p>
                </div>
            @endif

            <div class="card-footer text-center">
                <a href="{{ route('cities.index') }}" class="btn btn-secondary btn-lg">
                    ← Volver a la lista
                </a>
            </div>
        </div>
    </div>
</div>
@endsection