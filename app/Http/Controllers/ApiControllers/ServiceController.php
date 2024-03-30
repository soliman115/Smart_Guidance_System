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
    public function index(Request $request)
    {
        $pageSize = $request->page_size;
        $Service = Service::paginate($pageSize);
        return $this->apiResponse($Service, "ok", 200);

        // $Service= ServiceResource::collection(Service::paginate(10));
        // $array = [
        //     'Service'=>$Service,
        //     'msg'=>"good",
        //     'status'=>200
        // ];
        // return response($array);
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
    public function getservicebyplace($id, Request $request)
    {
        // Find the place by its ID
        $place = Place::find($id);

        // Check if the place exists
        if ($place) {
            // Get the per-page value from the request
            $perPage = $request->input('per_page', 10); // Default to 10 if not provided

            // Get services for the place with pagination
            $services = $place->services()->paginate($perPage);

            // If services are found, return them
            if ($services->count() > 0) {
                return $this->apiResponse($services, "Services found for place", 200);
            } else {
                // If no services found for the place, return a message
                return $this->apiResponse(null, "No services found for the specified page", 404);
            }
        } else {
            // If the place doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid place ID", 404);
        }
    }

    public function getemployeebyplace($id, Request $request)
    {
        // Find the place by its ID
        $place = Place::find($id);

        // Check if the place exists
        if ($place) {
            // Get the per-page value from the request
            $perPage = $request->input('per_page', 10); // Default to 10 if not provided

            // Get employees for the place with pagination
            $employees = $place->employees()->paginate($perPage);

            // If employees are found, return them
            if ($employees->count() > 0) {
                return $this->apiResponse($employees, "Employees found for place", 200);
            } else {
                // If no employees found for the place, return a message
                return $this->apiResponse(null, "No employees found for the specified page", 404);
            }
        } else {
            // If the place doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid place ID", 404);
        }
    }

    public function getservicebyemployee($id, Request $request)
    {
        // Find the employee by their ID
        $employee = Employee::find($id);

        // Check if the employee exists
        if ($employee) {
            // Get the per-page value from the request
            $perPage = $request->input('per_page', 10); // Default to 10 if not provided

            // Get services for the employee with pagination
            $services = $employee->services()->paginate($perPage);

            // If services are found, return them
            if ($services->count() > 0) {
                return $this->apiResponse($services, "Services found for employee", 200);
            } else {
                // If no services found for the employee, return a message
                return $this->apiResponse(null, "No services found for the specified page", 404);
            }
        } else {
            // If the employee doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid employee ID", 404);
        }
    }

    public function getplacebyemployee($id, Request $request)
    {
        // Find the employee by their ID
        $employee = Employee::find($id);

        // Check if the employee exists
        if ($employee) {
            // Get the per-page value from the request
            $perPage = $request->input('per_page', 10); // Default to 10 if not provided

            // Get the place for the employee with pagination
            $place = $employee->place()->paginate($perPage);

            // If place is found, return it
            if ($place) {
                return $this->apiResponse($place, "Place found for employee", 200);
            } else {
                // If no place found for the employee, return a message
                return $this->apiResponse(null, "No place found for the specified page", 404);
            }
        } else {
            // If the employee doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid employee ID", 404);
        }
    }

    public function getemployeebyservice($id, Request $request)
    {
        // Find the service by its ID
        $service = Service::find($id);

        // Check if the service exists
        if ($service) {
            // Get the per-page value from the request
            $perPage = $request->input('per_page', 10); // Default to 10 if not provided

            // Get employees for the service with pagination
            $employees = $service->employees()->paginate($perPage);

            // If employees are found, return them
            if ($employees->count() > 0) {
                return $this->apiResponse($employees, "Employees found for service", 200);
            } else {
                // If no employees found for the service, return a message
                return $this->apiResponse(null, "No employees found for the specified page", 404);
            }
        } else
        {// If the service doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid service ID", 404);
        }
    }
            // If the service doesn't exist, return a

    public function getplacebyservice($id, Request $request)
    {
        // Find the service by its ID
        $service = Service::find($id);

        // Check if the service exists
        if ($service) {
            // Get the per-page value from the request
            $perPage = $request->input('per_page', 10); // Default to 10 if not provided

            // Get the place for the service with pagination
            $place = $service->place()->paginate($perPage);

            // If place is found, return it
            if ($place) {
                return $this->apiResponse($place, "Place found for service", 200);
            } else {
                // If no place found for the service, return a message
                return $this->apiResponse(null, "No place found for the specified page", 404);
            }
        } else {
            // If the service doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid service ID", 404);
        }
    }

    public function getbuildingbyplace($id, Request $request)
    {
        // Find the place by its ID
        $place = Place::find($id);

        // Check if the place exists
        if ($place) {
            // Get the per-page value from the request
            $perPage = $request->input('per_page', 10); // Default to 10 if not provided

            // Get the building for the place with pagination
            $building = $place->building()->paginate($perPage);

            // If building is found, return it
            if ($building) {
                return $this->apiResponse($building, "Building found for place", 200);
            } else {
                // If no building found for the place, return a message
                return $this->apiResponse(null, "No building found for the specified page", 404);
            }
        } else {
            // If the place doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid place ID", 404);
        }
    }

    public function getplacebybuilding($id, Request $request)
    {
        // Find the building by its ID
        $building = Building::find($id);

        // Check if the building exists
        if ($building) {
            // Get the per-page value from the request
            $perPage = $request->input('per_page', 10); // Default to 10 if not provided

            // Get places for the building with pagination
            $places = $building->places()->paginate($perPage);

            // If places are found, return them
            if ($places->count() > 0) {
                return $this->apiResponse($places, "Places found for building", 200);
            } else {
                // If no places found for the building, return a message
                return $this->apiResponse(null, "No places found for the specified page", 404);
            }
        } else {
            // If the building doesn't exist, return a message indicating that the ID is invalid
            return $this->apiResponse(null, "Invalid building ID", 404);
        }
    }
}
