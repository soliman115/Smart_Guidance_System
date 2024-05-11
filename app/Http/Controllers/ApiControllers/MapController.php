<?php

namespace App\Http\Controllers\ApiControllers;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\Map;
use App\Http\Resources\MapResource;
use App\Http\Resources\MapCollection;

use Illuminate\Support\Facades\Storage;

class MapController extends Controller
{
    // public function show($id)
    // {
    //     $map = Map::findOrFail($id); // Using findOrFail to automatically handle 404 error
    //     return new MapResource($map);
    // }

    // public function index()
    // {
    //     $maps = Map::all();
    //     return new MapResource(MapResource::collection($maps));
    // }

    //retrive Maps
    

    public function getMaps()
    {
        // Retrieve all maps
        $maps = MapResource::collection(Map::all());

        // Read the contents of the MapBackground JSON file
        $backgroundJson = file_get_contents(storage_path('mapBackground.json'));

        // Decode the JSON into an associative array
        $backgroundArray = json_decode($backgroundJson, true);

        // Combine the background array and the maps collection into a new array
        $data = [
            'background' => $backgroundArray,
            'map' => $maps->toArray(new Request()) // Pass an empty Request instance
        ];

        // Create the response array
        $response = [
            'data' => $data,
            'msg' => "good",
            'status' => 200
        ];

        return response()->json($response);
    }
}

