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
        'latitude'  => 'required',
        'longitude' => 'required',
        'image'     => 'nullable|image|max:2048'
    ]);

    $lat = $this->parseCoordinate($request->latitude);
    $lon = $this->parseCoordinate($request->longitude);

    if ($lat === null || $lon === null) {
        return back()->withErrors([
            'latitude'  => 'Formato de latitud no reconocido',
            'longitude' => 'Formato de longitud no reconocido'
        ])->withInput();
    }

    if ($lat < -90 || $lat > 90 || $lon < -180 || $lon > 180) {
        return back()->withErrors([
            'latitude'  => 'Latitud debe estar entre -90 y 90',
            'longitude' => 'Longitud debe estar entre -180 y 180'
        ])->withInput();
    }

    $data = [
        'name'      => $request->name,
        'latitude'  => $lat,
        'longitude' => $lon,
    ];

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('cities', 'public');
    }

    City::create($data);

    return redirect()->route('cities.index')->with('success', 'Ciudad agregada correctamente');
}

/**
 * Convierte CUALQUIER formato de coordenada a decimal
 * Acepta:
 *   4.60971
 *   4.6097°
 *   4.6097° N
 *   4° 36.582' N
 *   4°36'35"N
 *   -74.08175
 *   74.08175°W
 *   4,60971 (coma como separador)
 *   etc.
 */
private function parseCoordinate(string $input): ?float
{
    $original = $input;
    $input = trim($input);

    // 1. Reemplaza coma por punto (formato europeo)
    $input = str_replace(',', '.', $input);

    // 2. Quita todos los caracteres que no sean números, puntos, guiones o espacios
    $clean = preg_replace('/[^\d.\-\s]/', '', $input);

    // 3. Si después de limpiar quedó un número válido → devolverlo
    if (is_numeric($clean) && $clean >= -180 && $clean <= 180) {
        return (float) $clean;
    }

    // 4. Caso grados + minutos + segundos: 4°36'35" N
    if (preg_match("/(\d+)°\s*(\d+)'\s*([\d.]+)\"?\s*([NSEW])/i", $original, $m)) {
        $deg = $m[1];
        $min = $m[2];
        $sec = $m[3];
        $dir = strtoupper($m[4]);
        $decimal = $deg + $min/60 + $sec/3600;
        return ($dir === 'S' || $dir === 'W') ? -$decimal : $decimal;
    }

    // 5. Caso grados + minutos decimales: 4° 36.582' N
    if (preg_match("/(\d+)°\s*([\d.]+)'\s*([NSEW])/i", $original, $m)) {
        $deg = $m[1];
        $min = $m[2];
        $dir = strtoupper($m[3]);
        $decimal = $deg + $min/60;
        return ($dir === 'S' || $dir === 'W') ? -$decimal : $decimal;
    }

    // 6. Caso solo grados con dirección: 4.6097° N
    if (preg_match("/([\d.]+)°\s*([NSEW])/i", $original, $m)) {
        $decimal = (float)$m[1];
        $dir = strtoupper($m[2]);
        return ($dir === 'S' || $dir === 'W') ? -$decimal : $decimal;
    }

    // 7. Si nada funcionó → inválido
    return null;
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