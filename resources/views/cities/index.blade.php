<!DOCTYPE html>
<html>
<head><title>Ciudades</title></head>
<body>
    <h1>Ciudades Registradas</h1>
    <a href="{{ route('cities.create') }}">+ Agregar nueva ciudad</a>
    <hr>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="10">
        <tr>
            <th>Nombre</th>
            <th>Lat/Lon</th>
            <th>Imagen</th>
            <th>Acción</th>
        </tr>
        @foreach($cities as $city)
        <tr>
            <td>{{ $city->name }}</td>
            <td>{{ $city->latitude }}, {{ $city->longitude }}</td>
            <td>
                @if($city->image)
                    <img src="{{ asset('storage/'.$city->image) }}" width="100">
                @else
                    Sin imagen
                @endif
            </td>
            <td>
                <a href="{{ route('cities.show', $city) }}">Ver Clima</a>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>