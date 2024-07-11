<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MapController extends Controller
{
    private $overpassApiUrl = 'https://overpass-api.de/api/interpreter';

    public function index()
    {
        // Define the 10 points of interest
        $pointsOfInterest = [
            ['lat' => 42.8071287, 'lon' => -8.446856, 'name' => 'Pico Sacro'],
            ['lat' => 42.7949627, 'lon' => -8.4820096, 'name' => 'Fervenza do Pereiro'],
            ['lat' => 42.7849458, 'lon' => -8.3969719,868, 'name' => 'Mirador de Gundian'],
            ['lat' => 42.7449678, 'lon' => -8.4670154, 'name' => 'Coto de Ximonde'],
            ['lat' => 42.7942116, 'lon' => -8.3368867, 'name' => 'Ponteledesma'],
            ['lat' => 42.7456804, 'lon' => -8.4342679, 'name' => 'Areal de Berres'],
            ['lat' => 42.7950889, 'lon' => -8.4037588, 'name' => 'Fervenza de Codeso'],
            ['lat' => 42.7782743, 'lon' => -8.4051128, 'name' => 'Ponte do Rio Ulla'],
            ['lat' => 42.7806975, 'lon' => -8.3910305,1161, 'name' => 'Mirador Do Alto do Castro'],
            ['lat' => 42.7713894, 'lon' => -8.4258853,205, 'name' => 'Pazo de Santa Cruz de Rivadulla']
        ];

        return view('map', [
            'pointsOfInterest' => $pointsOfInterest,  // Pass specific points of interest to the view
        ]);
    }
    #inicio de los botones en pantalla + funcionalidad API Overpass
    public function fetchRestaurants(Request $request)
    {
        $query = '[out:json][timeout:25];
        node["amenity"="restaurant"](42.661736441708754,-8.54290008544922,42.898603647672864,-8.180694580078127);
        out geom;';
        
        $response = Http::withOptions([
        ])->post($this->overpassApiUrl, [
            'data' => $query
        ]);

        $data = $response->json();
        
        return response()->json($data);
    }

    public function fetchWater(Request $request)
    {
        $query = '[out:json][timeout:25];
        node["amenity"="drinking_water"](42.661736441708754,-8.54290008544922,42.898603647672864,-8.180694580078127);
        out geom;';
        
        $response = Http::withOptions([
    
        ])->post($this->overpassApiUrl, [
            'data' => $query
        ]);

        $data = $response->json();
        
        return response()->json($data);
    }


    
    public function fetchFuel(Request $request)
    {
        $query = '[out:json][timeout:25];
        node["amenity"="fuel"](42.661736441708754,-8.54290008544922,42.898603647672864,-8.180694580078127);
        out geom;';
        
        $response = Http::withOptions([
        ])->post($this->overpassApiUrl, [
            'data' => $query
        ]);

        $data = $response->json();
        
        return response()->json($data);
    }

    public function fetchHotels(Request $request)
    {
        $query = '[out:json][timeout:25];
        node["tourism"="hotel"](42.661736441708754,-8.54290008544922,42.898603647672864,-8.180694580078127);
        out geom;';
        
        $response = Http::withOptions([
        ])->post($this->overpassApiUrl, [
            'data' => $query
        ]);

        $data = $response->json();
        
        return response()->json($data);
    }

    public function fetchParking(Request $request)
    {
        $query = '[out:json][timeout:25];
        node["amenity"="parking"]["parking"="surface"](42.661736441708754,-8.54290008544922,42.898603647672864,-8.180694580078127);
        out geom;';
        
        $response = Http::withOptions([
        ])->post($this->overpassApiUrl, [
            'data' => $query
        ]);

        $data = $response->json();
        
        return response()->json($data);
    }

    #traducir input para API overpass en boton buscar
    public function fetchSearch(Request $request)
    {
        $query = $request->input('query');

        $translations = [
            'restaurante' => 'amenity=restaurant',
            'agua_potable' => 'amenity=drinking_water',
            'combustible' => 'amenity=fuel',
            'hotel' => 'tourism=hotel',
            'aparcamientos' => 'amenity=parking["parking"="surface"]',
            'estacionamiento' => 'amenity=parking["parking"="surface"]',
            'parque' => 'leisure=park',
            'biblioteca' => 'amenity=library',
            'iglesia' => 'amenity=place_of_worship',
            'museo' => 'tourism=museum',
            'centro_de_salud' => 'amenity=clinic',
            'farmacia' => 'amenity=pharmacy',
            'restaurante_vegetariano' => 'amenity=restaurant["cuisine"="vegetarian"]',
            'cafe' => 'amenity=cafe',
            'escuela' => 'amenity=school',
            'supermercado' => 'shop=supermarket',
            'tienda' => 'shop=*',
            'hospital' => 'amenity=hospital',
            'banio' => 'amenity=toilets',
            'zona_de_ocio' => 'leisure=*',
            'sede' => 'office=*',
        ];
        #reemplazar espacios con _ para overpass
        $query = strtolower($query);
        $translatedQuery = $translations[$query] ?? 'amenity=' . str_replace(' ', '_', $query);
        list($type, $value) = explode('=', $translatedQuery, 2);
        
        $dynamicQuery = "[out:json][timeout:25];node[\"$type\"=\"$value\"](42.661736441708754,-8.54290008544922,42.898603647672864,-8.180694580078127);out geom;";

        $response = Http::withOptions([
        ])->post($this->overpassApiUrl, [
            'data' => $dynamicQuery
        ]);

        $data = $response->json();
        
        return response()->json($data);
    }
}
