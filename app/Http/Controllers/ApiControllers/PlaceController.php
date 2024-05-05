<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Place;
use App\Http\Resources\PlaceResource;
use Illuminate\Support\Facades\Validator;

class PlaceController extends Controller

{
        // insert place
        public function insertPlace(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'id' => 'required|unique:places',
                'name' => 'required|unique:places',
                'region_id' => 'required|exists:regions,id', // Check if region_id exists in Region model
                'x_coordinate' => 'required',
                'y_coordinate' => 'required',
                'building_id' => 'required'
            ]);

            if ($validator->fails()) {
                return "Error: " . $validator->errors();
            }

            Place::create($request->all());

            $id = $request->id;
            return "Record with id $id inserted successfully!";
        }//end insert place


        //retrive places
        public function getPlaces(Request $request){
            $pageSize = $request->page_size;
            $places= PlaceResource::collection(place::paginate($pageSize));
            $array = [
                'places'=>$places,
                'msg'=>"good",
                'status'=>200
            ];
            return response($array);
        }//end retrive

        //update places
        public function updatePlace(Request $request, $id)
        {
            $validator = Validator::make($request->all(), [
                'id' => 'required|unique:places,id,'.$request->id,
                'name' => 'required|unique:places,name',
                'region_id' => 'required|exists:regions,id',
                'x_coordinate' => 'required',
                'y_coordinate' => 'required',
                'building_id' => 'required'
            ]);
            
            

            
            if ($validator->fails()) {
                return "Error: " . $validator->errors();
            }

            $place = Place::find($request->id);
            if (!$place) {
                return "Error: Record with id $request->id not found!";
            }

            $place->update($request->all());
            return "Record with id $request->id updated successfully!";
        }//end update

        //delete places
        public function deletePlace($id)
        {
            $place = Place::find($id);
            if (!$place) {
                return "Error: Record with id $id not found!";
            }

            $place->delete();

            return "Record with id $id deleted successfully!";
        }//end delete

}//end class
