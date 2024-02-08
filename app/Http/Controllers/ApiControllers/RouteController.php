<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Route;
use App\Http\Resources\RouteResource;
use Illuminate\Support\Facades\Validator;
use App\Models\Place;
use App\Models\Region;


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
    Route::create($request->all());
    $id = $request->id;
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

//get best path function
public function getBestPass(string $source , string $destinationPlace)
{
    $place= Place::find($destinationPlace);
    $destinationRegion = $place->Region;
    $source=Region::find($source);

    return [$source->name,$destinationRegion->name];

}//end 




}






