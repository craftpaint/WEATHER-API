<!DOCTYPE html>
<html>
<head><title>Agregar Ciudad</title></head>
<body>
    <h1>Registrar Ciudad Colombiana</h1>
    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    @endif

    <form action="{{ route('cities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <p>Nombre: <input type="text" name="name" value="{{ old('name') }}" required></p>
        <p>Latitud: <input type="text" name="latitude" value="{{ old('latitude') }}" placeholder="Ej: 4.6097 o 4°36'35&quot;N" required></p>
        <p>Longitud: <input type="text" name="longitude" value="{{ old('longitude') }}" placeholder="Ej: -74.08 o 74°04'54&quot;W" required></p>
        <p>Imagen: <input type="file" name="image"></p>
        <button type="submit">Guardar Ciudad</button>
    </form>
    <a href="{{ route('cities.index') }}">Volver</a>
</body>
</html>