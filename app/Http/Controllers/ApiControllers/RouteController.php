<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Route;
use App\Http\Resources\RouteResource;
use Illuminate\Support\Facades\Validator;
use App\Models\Place;
use App\Models\Region;
use App\GraphUtility;



class RouteController extends Controller
{
    //insert routes
    public function insertRoute(Request $request)
{
    // Validate input fields
    $validator = Validator::make($request->all(), [
        'source' => 'required|string',
        'destination' => 'required|string',
        'next_step' => 'required|string',
        'direction' => 'required|string',
        'distance' => 'required|integer'
    ]);

    if ($validator->fails()) {
        return "Error: " . $validator->errors();
    }
    $route = Route::create($request->all());
    $id = $route->id; // Get the ID of the created record
    return "Record with ID $id inserted successfully!";
}
// end insert


//retrive Routes
    public function getRoutes(){
        $Routes= RouteResource::collection(Route::get());
        $array = [
            'Routes'=>$Routes,
            'msg'=>"good",
            'status'=>200
        ];
        return response($array);
    }//end retrive

    //update Routes
    public function updateRoute(Request $request, $id)
    { 
        $validator = Validator::make($request->all(), [
            'source' => 'required|string',
            'destination' => 'required|string',
            'next_step' => 'required|string',
            'direction' => 'required|string',
            'distance' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return "Error: " . $validator->errors();
        }

        $Route = Route::find($id);
        if (!$Route) {
            return "Error: Record with id $id not found!";
        }
        
        $Route->update($request->all());
        //$Route= Route::create($validator->validated());
        return "Record with id $id updated successfully!";
    }//end update



    //delete Routes
    public function deleteRoute($id)
    {
        $Route = Route::find($id);
        if (!$Route) {
            return "Error: Record with id $id not found!";
        }

        $Route->delete();

        return "Record with id $id deleted successfully!";
    }//end delete

    //return the graphcreated based on routing table -just For clarification-
    public function returnGraph(){$graph = GraphUtility::constructGraphFromDatabase(); return $graph;}

    //find best path based on the graph
    public function findBestPath( $source ,$destinationPlace) {
        $source_s = $source;
        $destination_d = $destinationPlace;

        $navigationData = GraphUtility::findShortestPath($source_s ,$destination_d);
        $pathWithInstructions = GraphUtility::generateNavigationInstructions($navigationData);


        $ttsText = $pathWithInstructions['instructions']; 
        $instructionsAudio = GraphUtility::generateMP3FromText($ttsText );
        
        //append the instructionsAudio to pathWithInstructions
        $pathWithInstructions['instructionsAudio'] = $instructionsAudio;

        // Return the combined pathWithInstructions
        return $pathWithInstructions;

    }//end find best path 


    

}






