@extends('layouts.app')
@section('title', 'Editar Ciudad')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-warning text-white text-center py-4">
                <h3 class="mb-0">Editar {{ $city->name }}</h3>
            </div>

            <div class="card-body p-5">
                <form action="{{ route('cities.update', $city) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- ESTAS DOS LÍNEAS SON OBLIGATORIAS Y DEBEN ESTAR JUSTO AQUÍ -->

                    <div class="mb-4">
                        <label class="form-label fw-bold">Nombre</label>
                        <input type="text" name="name" class="form-control form-control-lg"
                               value="{{ old('name', $city->name) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Latitud (solo formato numerico)</label>
                        <input type="text" name="latitude" class="form-control form-control-lg"
                               value="{{ old('latitude', $city->latitude) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Longitud (solo formato numerico)</label>
                        <input type="text" name="longitude" class="form-control form-control-lg"
                               value="{{ old('longitude', $city->longitude) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Nueva imagen (opcional)</label>
                        @if($city->image)
                            <img src="{{ asset('storage/'.$city->image) }}" class="img-thumbnail mb-3" style="max-height:200px;">
                        @endif
                        <input type="file" name="image" class="form-control form-control-lg" accept="image/*">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-warning btn-lg px-5">Guardar Cambios</button>
                        <a href="{{ route('cities.index') }}" class="btn btn-secondary btn-lg ms-3">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection