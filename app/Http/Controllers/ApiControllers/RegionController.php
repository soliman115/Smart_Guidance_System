<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Region;
use App\Models\place;
use App\Http\Resources\RegionResource;

class RegionController extends Controller
{
    //
    //function to insert Regions
    public function insertRegion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|unique:Regions',
            'name' => 'required|unique:Regions',
            'x_coordinate' => 'required',
            'y_coordinate' => 'required'
        ]);

        if ($validator->fails()) {
            return "Error: " . $validator->errors();
        }
        Region::create($request->all());
        $region= $request->region_name;
        return "Record with region  $region inserted successfully!";
    }//end insert

//function to retrive Regions
public function getRegions(){
    $Regions= RegionResource::collection(Region::get());
    $array = [
        'Regions'=>$Regions,
        'msg'=>"good",
        'status'=>200
    ];
    return response($array);
}//end retrive

//function to update Regions
public function updateRegion(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
            'id' => 'required|unique:Regions',
            'name' => 'required|unique:Regions',
            'x_coordinate' => 'required',
            'y_coordinate' => 'required'
    ]);

    if ($validator->fails()) {
        return "Error: " . $validator->errors();
    }

    $Region = Region::find($id);
    if (!$Region) {
        return "Error: Record with id $id not found!";
    }
    
    $Region->update($request->all());
   
    //$Region= Region::create($validator->validated());

    return "Record with id $id updated successfully!";


}//end update



//function to delete Regions
public function deleteRegion($id)
{
    $Region = Region::find($id);
    if (!$Region) {
        return "Error: Record with id $id not found!";
    }

    $Region->delete();

    return "Record with id $id deleted successfully!";
}//end delete





    public function test_fun(){
        // $region = Region::find('6');
        // $Region = $region->places;
        // return $Region; 

        $place= Place::find('8');
        $destinationRegion = $place->Region;
        return [$destinationRegion];
    } 
}
