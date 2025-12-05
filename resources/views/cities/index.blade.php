@extends('layouts.app')
@section('title', 'Ciudades Registradas')

@section('content')
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5">
    @forelse($cities as $city)
        <div class="col">
            <div class="card h-100 border-0 shadow-lg">
                <!-- Imagen o placeholder -->
                @if($city->image)
                    <img src="{{ asset('storage/'.$city->image) }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 250px;">
                        <h1 class="display-4 opacity-25">{{ Str::upper(substr($city->name, 0, 2)) }}</h1>
                    </div>
                @endif

                <!-- Cuerpo de la tarjeta -->
                <div class="card-body text-center py-5">
                    <h3 class="card-title fw-bold mb-4 fs-2">{{ $city->name }}</h3>
                    
                    <div class="d-grid gap-3">
                        <a href="{{ route('cities.show', $city) }}" class="btn btn-primary btn-lg">
                            Ver Clima Actual
                        </a>
                        <a href="{{ route('cities.edit', $city) }}" class="btn btn-warning btn-lg text-white">
                            Editar Ciudad
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <h2 class="text-white mb-4">No hay ciudades registradas</h2>
            <a href="{{ route('cities.create') }}" class="btn btn-light btn-lg px-5 py-3">
                <i class="bi bi-plus-circle"></i> Registrar la primera ciudad
            </a>
        </div>
    @endforelse
</div>
@endsection