<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::all();
        return view('cities.index', compact('cities'));
    }

    public function create()
    {
        return view('cities.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name'      => 'required|unique:cities|max:100',
        'latitude'  => 'required',  // ya no validamos como numeric aquí
        'longitude' => 'required',
        'image'     => 'nullable|image|max:2048'
    ]);

    // Función mágica que limpia cualquier formato de coordenada
    $cleanLatitude  = $this->cleanCoordinate($request->latitude);
    $cleanLongitude = $this->cleanCoordinate($request->longitude);

    // Validación final: ahora sí deben ser números válidos
    if (!is_numeric($cleanLatitude) || !is_numeric($cleanLongitude)) {
        return back()->withErrors([
            'latitude'  => 'Latitud inválida',
            'longitude' => 'Longitud inválida'
        ])->withInput();
    }

    if ($cleanLatitude < -90 || $cleanLatitude > 90 || $cleanLongitude < -180 || $cleanLongitude > 180) {
        return back()->withErrors([
            'latitude'  => 'Latitud debe estar entre -90 y 90',
            'longitude' => 'Longitud debe estar entre -180 y 180'
        ])->withInput();
    }

    $data = [
        'name'      => $request->name,
        'latitude'  => $cleanLatitude,
        'longitude' => $cleanLongitude,
    ];

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('cities', 'public');
    }

    City::create($data);

    return redirect()->route('cities.index')->with('success', 'Ciudad agregada correctamente');
}

/**
 * Limpia cualquier formato de coordenada y devuelve solo el número decimal
 * Ejemplos aceptados:
 *   4.60971
 *   4.6097°
 *   4°36'35"N
 *   -74.0817
 *   74°04'54"W
 */
private function cleanCoordinate($coord)
{
    // Quita todo lo que no sea número, punto, coma, menos o espacio
    $clean = preg_replace('/[^\d.,-]/', '', $coord);
    // Cambia coma por punto (por si alguien usa formato europeo)
    $clean = str_replace(',', '.', $clean);
    // Quita espacios
    $clean = trim($clean);

    // Si tiene grados, minutos y segundos (ej: 4°36'35"), lo convierte a decimal
    if (preg_match("/(\d+)°\s*(\d+)'\s*(\d+(\.\d+)?)\"/", $coord, $matches)) {
        $degrees = $matches[1];
        $minutes = $matches[2];
        $seconds = $matches[3];
        $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);
        // Aplica signo si viene con S o W
        if (stripos($coord, 'S') !== false || stripos($coord, 'W') !== false) {
            $decimal = -$decimal;
        }
        return $decimal;
    }

    return $clean;
}

    public function show(City $city)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'lat'    => $city->latitude,
            'lon'    => $city->longitude,
            'appid' => env('OPENWEATHER_API_KEY'),
            'units'  => 'metric',
            'lang'   => 'es'
        ]);

        $weather = $response->json();

        return view('cities.show', compact('city', 'weather'));
    }
}