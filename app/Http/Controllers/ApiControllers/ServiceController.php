<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Building;
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
        // Find the place by its ID
        $place = Place::find($id);

        // Check if the place exists
        if ($place) {
            // If the place exists, return its services
            return $this->apiResponse($place->services, "Services found for place", 200);
        } else {
            // If the place doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid place ID", 404);
        }
    }

    public function getemployeebyplace($id)
    {
        // Find the place by its ID
        $place = Place::find($id);

        // Check if the place exists
        if ($place) {
            // If the place exists, return its employees
            return $this->apiResponse($place->employees, "Employees found for place", 200);
        } else {
            // If the place doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid place ID", 404);
        }
    }

    public function getservicebyemployee($id)
    {
        // Find the employee by their ID
        $employee = Employee::find($id);

        // Check if the employee exists
        if ($employee) {
            // If the employee exists, return their services
            return $this->apiResponse($employee->services, "Services found for employee", 200);
        } else {
            // If the employee doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid employee ID", 404);
        }
    }

    public function getplacebyemployee($id)
    {
        // Find the employee by their ID
        $employee = Employee::find($id);

        // Check if the employee exists
        if ($employee) {
            // If the employee exists, return their place
            return $this->apiResponse($employee->place, "Place found for employee", 200);
        } else {
            // If the employee doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid employee ID", 404);
        }
    }

    public function getemployeebyservice($id)
    {
        // Find the service by its ID
        $service = Service::find($id);

        // Check if the service exists
        if ($service) {
            // If the service exists, return its employees
            return $this->apiResponse($service->employees, "Employees found for service", 200);
        } else {
            // If the service doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid service ID", 404);
        }
    }

    public function getplacebyservice($id)
    {
        // Find the service by its ID
        $service = Service::find($id);

        // Check if the service exists
        if ($service) {
            // If the service exists, return its place
            return $this->apiResponse($service->place, "Place found for service", 200);
        } else {
            // If the service doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid service ID", 404);
        }
    }

    public function getbuildingbyplace($id)
    {
        // Find the place by its ID
        $place = Place::find($id);

        // Check if the place exists
        if ($place) {
            // If the place exists, return its building
            return $this->apiResponse($place->building, "Building found for place", 200);
        } else {
            // If the place doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid place ID", 404);
        }
    }

    public function getplacebybuilding($id)
    {
        // Find the building by its ID
        $building = Building::find($id);

        // Check if the building exists
        if ($building) {
            // If the building exists, return its places
            return $this->apiResponse($building->places, "Places found for building", 200);
        } else {
            // If the building doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid building ID", 404);
        }
    }
}
