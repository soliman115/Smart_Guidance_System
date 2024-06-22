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
    //insert Regions
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
        $region_id= $request->id;
        return "Region with id  $region_id inserted successfully!";
    }//end insert

//retrive Regions
public function getRegions(){
    $Regions= RegionResource::collection(Region::get());
    $array = [
        'Regions'=>$Regions,
        'msg'=>"good",
        'status'=>200
    ];
    return response($array);
}//end retrive

//update Regions
public function updateRegion(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
            'id' => 'required|unique:Regions,id,'.$request->id,
            'name' => 'required|unique:places,name',
            'x_coordinate' => 'required',
            'y_coordinate' => 'required'
    ]);

    if ($validator->fails()) {
        return "Error: " . $validator->errors();
    }

    $Region = Region::find($request->id);
    if (!$Region) {
        return "Error: Record with id $request->id not found!";
    }
    
    $Region->update($request->all());

    return "Region with id $request->id updated successfully!";
}//end update

//delete Regions
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
    $place= Place::find('8');
    $destinationRegion = $place->Region;
    return [$destinationRegion];
    } 
}
