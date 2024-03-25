<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $pageSize = $request->page_size;
        $employees = Employee::paginate($pageSize);
        return $this->apiResponse($employees, "ok", 200);
        // $employees= EmployeeResource::collection(Employee::paginate($pageSize));
        // $array = [
        //     'employees'=>$employees,
        //     'msg'=>"good",
        //     'status'=>200
        // ];
        // return response($array);
    }

    public function show($id)
    {
        $employee = Employee::find($id);

        if ($employee) {
            return $this->apiResponse(new EmployeeResource($employee), 'ok', 200);
        }

        return $this->apiResponse(null, 'The Employee Not Found', 401);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'place_id' => 'required',
            'employee_job' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $employeeData = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->storeAs('employeesImgs', $photo->getClientOriginalName(), 'imgs');
            $employeeData['photo'] = $photoPath;
        }

        $employee = Employee::create($employeeData);

        if ($employee) {
            return $this->apiResponse(new EmployeeResource($employee), 'The Employee is saved', 201);
        }

        return $this->apiResponse(null, 'The Employee is not saved', 400);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'place_id' => 'required',
            'employee_job' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $employee = Employee::find($id);

        if (!$employee) {
            return $this->apiResponse(null, 'The Employee is not found', 404);
        }

        $employeeData = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete the existing photo
            if ($employee->photo) {
                Storage::disk('imgs')->delete($employee->photo);
            }

            $photo = $request->file('photo');
            $photoPath = $photo->storeAs('employeesImgs', $photo->getClientOriginalName(), 'imgs');
            $employeeData['photo'] = $photoPath;
        }

        $employee->update($employeeData);

        if ($employee) {
            return $this->apiResponse(new EmployeeResource($employee), 'The Employee is updated', 200);
        }
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return $this->apiResponse(null, 'The Employee is not found', 404);
        }

        // Delete the photo file
        if ($employee->photo) {
            Storage::disk('imgs')->delete($employee->photo);
        }

        $employee->delete();

        return $this->apiResponse(null, 'The Employee is deleted', 200);
    }
}

