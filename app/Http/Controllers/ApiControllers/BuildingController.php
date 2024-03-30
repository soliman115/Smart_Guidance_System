<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\BuildingResource;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BuildingController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $pageSize = $request->page_size;
        $buildings = Building::paginate($pageSize);
        return $this->apiResponse($buildings, "ok", 200);

        // $buildings= BuildingResource::collection(Building::paginate(10));
        //     $array = [
        //         'buildings'=>$buildings,
        //         'msg'=>"good",
        //         'status'=>200
        //     ];
        //     return response($array);

    }


    public function show($id)
    {
        $building = Building::find($id);

        if ($building) {
            return $this->apiResponse(new BuildingResource($building), 'ok', 200);
        }

        return $this->apiResponse(null, 'The Building Not Found', 401);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'address' => 'required',
            'description' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $buildingData = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->storeAs('buildingsImgs', $photo->getClientOriginalName(), 'imgs');
            $buildingData['photo'] = $photoPath;
        }

        $building = Building::create($buildingData);

        if ($building) {
            return $this->apiResponse(new BuildingResource($building), 'The Building is saved', 201);
        }

        return $this->apiResponse(null, 'The Building is not saved', 400);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'address' => 'required',
            'description' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $building = Building::find($id);

        if (!$building) {
            return $this->apiResponse(null, 'The Building is not found', 404);
        }

        $buildingData = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete the existing photo
            if ($building->photo) {
                Storage::disk('imgs')->delete($building->photo);
            }

            $photo = $request->file('photo');
            $photoPath = $photo->storeAs('buildingsImgs', $photo->getClientOriginalName(), 'imgs');
            $buildingData['photo'] = $photoPath;
        }

        $building->update($buildingData);

        if ($building) {
            return $this->apiResponse(new BuildingResource($building), 'The Building is updated', 200);
        }
    }

    public function destroy($id)
    {
        $building = Building::find($id);

        if (!$building) {
            return $this->apiResponse(null, 'The Building is not found', 404);
        }

        // Delete the photo file
        if ($building->photo) {
            Storage::disk('imgs')->delete($building->photo);
        }

        $building->delete();

        return $this->apiResponse(null, 'The Building is deleted', 200);
    }
}
