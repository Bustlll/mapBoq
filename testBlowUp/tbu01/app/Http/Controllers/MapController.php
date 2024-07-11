<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MapController extends Controller
{
    public function index()
    {
        $overpassApiUrl = 'https://overpass-api.de/api/interpreter';
        $query = '[out:json][timeout:25];node["amenity"="drinking_water"](42.66729049697171,-8.568992614746096,42.90413649491736,-8.206787109375002);out geom;';
        
        $response = Http::asForm()->post($overpassApiUrl, [
            'data' => $query
        ]);

        $data = $response->json();

        return view('map', ['data' => $data]);
    }
}
