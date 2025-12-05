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
    $original = trim($input);

    if (empty($original)) {
        return null;
    }

    // 1. Normalizar: comas → puntos, quitar comillas dobles y simples, espacios extra
    $clean = str_replace([',', '″', '"', "''", "”", "“"], '.', $original);
    $clean = preg_replace('/\s+/', ' ', $clean); // múltiples espacios → uno solo
    $clean = trim($clean);

    // 2. Extraer dirección (N/S/E/W) si existe (puede estar al principio o al final)
    $direction = null;
    if (preg_match('/([NSWE])/i', $clean, $m)) {
        $direction = strtoupper($m[1]);
        $clean = preg_replace('/[NSWE]/i', '', $clean); // quitar la letra
        $clean = trim($clean);
    }

    // 3. Caso directo: número decimal puro (ej: 4.60971 o -74.08175)
    if (is_numeric($clean)) {
        $value = (float) $clean;
    }
    // 4. Grados + minutos + segundos → decimal (ej: 4°36'35" o 4° 36' 35.5")
    elseif (preg_match('/(\d+)°\s*(\d+)[\'\′]\s*([\d.]+)[\″"″]?/', $clean, $m)) {
        $value = $value = $m[1] + ($m[2] / 60) + ($m[3] / 3600);
    }
    // 5. Grados + minutos decimales → decimal (ej: 4° 36.582')
    elseif (preg_match('/(\d+)°?\s*([\d.]+)[\'\′]/', $clean, $m)) {
        $value = $m[1] + ($m[2] / 60);
    }
    // 6. Solo grados con símbolo ° (ej: 4.6097°)
    elseif (preg_match('/([\d.]+)°/', $clean, $m)) {
        $value = (float) $m[1];
    }
    // 7. Nada funcionó → inválido
    else {
        return null;
    }

    // Aplicar signo según dirección
    if ($direction && ($direction === 'S' || $direction === 'W')) {
        $value = -$value;
    }

    return (float) $value;
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