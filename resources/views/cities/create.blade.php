@extends('layouts.app')
@section('title', 'Nueva Ciudad')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg">
            <div class="card-header bg-success text-white text-center py-4">
                <h3>Registrar Ciudad Colombiana</h3>
            </div>
            <div class="card-body p-5">
                <form action="{{ route('cities.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold">Nombre de la ciudad</label>
                        <input type="text" name="name" class="form-control form-control-lg" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Latitud <small class="text-muted">(ej: 4.60971° N)</small></label>
                        <input type="text" name="latitude" class="form-control form-control-lg" value="{{ old('latitude') }}" placeholder="4.60971° N" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Longitud <small class="text-muted">(ej: -74.08175° W)</small></label>
                        <input type="text" name="longitude" class="form-control form-control-lg" value="{{ old('longitude') }}" placeholder="-74.08° W" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Imagen representativa (opcional)</label>
                        <input type="file" name="image" class="form-control form-control-lg" accept="image/*">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            Guardar Ciudad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection