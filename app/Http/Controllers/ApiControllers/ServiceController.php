<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Employee;
use App\Models\Place;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        $Service = Service::get();
        return $this->apiResponse($Service, "ok", 200);

        //return employees to the service
        // $Service = Service::find(1);
        // return $Service->employees;
        //  return service to the place
        // $place = Place::find(1);
        // return $place->services;

    }

    public function show($id)
    {
        $Service = Service::find($id);
        if ($Service) {
            return $this->apiResponse(new ServiceResource($Service), 'ok', 200);
        }
        return $this->apiResponse(null, 'The Service Not Found', 401);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'place_id' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
        $Service = Service::create($request->all());
        if ($Service) {
            return $this->apiResponse(new ServiceResource($Service), 'The Service Save', 201);
        }
        return $this->apiResponse(null, 'The Service Not Save', 400);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'place_id' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
        $Service = Service::find($id);
        if (!$Service) {
            return $this->apiResponse(null, 'The Service Not Found', 404);
        }
        $Service->update($request->all());
        if ($Service) {
            return $this->apiResponse(new ServiceResource($Service), 'The Service update', 201);
        }
    }
    public function destroy($id)
    {

        $Service = Service::find($id);

        if (!$Service) {
            return $this->apiResponse(null, 'The Service Not Found', 404);
        }

        $Service->delete($id);

        if ($Service) {
            return $this->apiResponse(null, 'The Service deleted', 200);
        }
    }
    public function getservicebyplace($id)
    {

        //return service to the place
        $place = Place::find($id);
        return $place->services;
    }
    public function getemployeebyplace($id)
    {
       //return employee to the place
        $place = Place::find($id);
        return $place->employees;

    }

    public function getservicebyemployee($id)
    {

        // return service to the employee
        $employee = Employee::find($id);
        return $employee->services;
    }

    public function getplacebyemployee($id)
    {

        //return place to the employee
        $employee = Employee::find($id);
        return $employee->place;
    }

    public function getemployeebyservice($id)
    {

         //return employees to the service
        $Service = Service::find($id);
        return $Service->employees;
    }
    public function getplacebyservice($id)
    {

         //return place to the service
        $Service = Service::find($id);
        return $Service->Place;
    }
    public function getbuildingbyplace($id)
    {

        //return building to the place
        $place = Place::find($id);
        return $place->building;
    }
}
