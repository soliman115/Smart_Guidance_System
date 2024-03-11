<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Place;
use App\Http\Resources\PlaceResource;
use Illuminate\Support\Facades\Validator;

class PlaceController extends Controller

{
        //insert places
        public function insertPlace(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'id' => 'required|unique:places',
                'name' => 'required|unique:places',
                'region' => 'required',
                'x_coordinate' => 'required',
                'y_coordinate' => 'required',
                'building_id' => 'required'
            ]);

            if ($validator->fails()) {
                return "Error: " . $validator->errors();
            }
                // $place = new Place;
                // $place->id = $request->id;
                // $place->name = $request->name;
                // $place->region = $request->region;
                // $place->guide_word = $request->guide_word;
                // $place->x_coordinate = $request->x_coordinate;
                // $place->y_coordinate = $request->y_coordinate;
                // $place->save();

                Place::create($request->all());
            
            // place::create($validator->validated());
            $id= $request->id;
            return "Record with id $id inserted successfully!";
        }//end insert

        //retrive places
        public function getPlaces(){
            $places= PlaceResource::collection(place::get());
            $array = [
                'places'=>$places,
                'msg'=>"good",
                'status'=>200
            ];
            return response($array);

           // service of place
        // $Service =Place::find(3)->Service;
        // return $Service;
           // employee of place
        // $employee =Place::find(3)->employee;
        // return $employee;

        }//end retrive

        //update places
        public function updatePlace(Request $request, $id)
        {
            $validator = Validator::make($request->all(), [
                'id' => 'required|unique:places,id,'.$id,
                'name' => 'required|unique:places,name,'.$id,
                'region_id' => 'required'
            ]);

            if ($validator->fails()) {
                return "Error: " . $validator->errors()->first();
            }

            $place = Place::find($id);
            if (!$place) {
                return "Error: Record with id $id not found!";
            }
            
            //$place->update($request->all());
            // $place->id = $request->id;
            // $place->name = $request->name;
            // $place->region = $request->region;
            // $place->guide_word = $request->guide_word;
            // $place->save();
            //$place= place::create($validator->validated());
            $place->update($request->all());
            return "Record with id $id updated successfully!";


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
