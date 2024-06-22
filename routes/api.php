<?php

use App\Http\Controllers\ApiControllers\AdminDashboardController;
use App\Http\Controllers\ApiControllers\Auth\ForgetPasswordController;
use App\Http\Controllers\ApiControllers\Auth\LoginController;
use App\Http\Controllers\ApiControllers\Auth\LogoutController;
use App\Http\Controllers\ApiControllers\Auth\ProfileController;
use App\Http\Controllers\ApiControllers\Auth\RegisterController;
use App\Http\Controllers\ApiControllers\Auth\ResetPasswordController;
use App\Http\Controllers\ApiControllers\Auth\UpdateProfileController;
use App\Http\Controllers\ApiControllers\BuildingController;
use App\Http\Controllers\ApiControllers\EmployeeController;
use App\Http\Controllers\ApiControllers\PlaceController;
use App\Http\Controllers\ApiControllers\RegionController;
use App\Http\Controllers\ApiControllers\RouteController;
use App\Http\Controllers\ApiControllers\ServiceController;
use App\Http\Controllers\ApiControllers\UserDashboardController;
use App\Http\Controllers\ApiControllers\VisitsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiControllers\MapController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// place routes
Route::get('/places',[PlaceController::class,'getPlaces']);
Route::post('/place',[PlaceController::class,'insertPlace']);
Route::put('/updateplace/{id}',[PlaceController::class,'updatePlace']);
Route::delete('/deleteplace/{id}',[PlaceController::class,'deletePlace']);
// route routes
Route::get('/routes',[RouteController::class,'getRoutes']);
Route::post('/route',[RouteController::class,'insertRoute']);
Route::put('/updateRoute/{id}',[RouteController::class,'updateRoute']);
Route::delete('/deleteRoute/{id}',[RouteController::class,'deleteRoute']);
// region routes
Route::post('/region',[RegionController::class,'insertRegion']);
Route::get('/region',[RegionController::class,'getRegions']);
Route::put('/updateRegion/{id}',[RegionController::class,'updateRegion']);
Route::delete('/deleteRegion/{id}',[RegionController::class,'deleteRegion']);
// find best path route
Route::get('/findShortestPath/{source}/{Destination}', [RouteController::class,'findBestPath']);
// return graph route
Route::get('/rgraph',[RouteController::class,'returnGraph']);
// return the map info
Route::get('/map', [MapController::class, 'getMaps']);
//getMaps


//CURD buildings

#get all building
Route::get('buildings', [BuildingController::class, 'index']);
#get one building by id
Route::get('/building/{id}', [BuildingController::class, 'show']);
#insert building
Route::post('building', [BuildingController::class, 'store']);
#update building
Route::post('updatebuilding/{id}', [BuildingController::class, 'update']);
#delete building
Route::post('/deletebuilding/{id}', [BuildingController::class, 'destroy']);


#CURD services
Route::get('services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::post('service', [ServiceController::class, 'store']);
Route::post('updateservice/{id}', [ServiceController::class, 'update']);
Route::post('/deleteservice/{id}', [ServiceController::class, 'destroy']);  //updated


//get service by place
Route::get('/servicestoplace/{id}', [ServiceController::class, 'getservicebyplace']);

//get employee by place
Route::get('/employeetoplace/{id}', [ServiceController::class, 'getemployeebyplace']);

//get service by employee
Route::get('/servicestoemployee/{id}', [ServiceController::class, 'getservicebyemployee']);

//get place by employee
Route::get('/placetoemployee/{id}', [ServiceController::class, 'getplacebyemployee']);

//get employee by service
Route::get('/employeetoservice/{id}', [ServiceController::class, 'getemployeebyservice']);

//get place by service
Route::get('/placetoservice/{id}', [ServiceController::class, 'getplacebyservice']);

//get building by place
Route::get('/buildingtoplace/{id}', [ServiceController::class, 'getbuildingbyplace']);

//get places by buding
Route::get('/placetobuilding/{id}', [ServiceController::class, 'getplacebybuilding']);

//CURD employees

Route::get('employees', [EmployeeController::class, 'index']);
Route::get('/employee/{id}', [EmployeeController::class, 'show']);
Route::post('employee', [EmployeeController::class, 'store']);
Route::post('updateemployee/{id}', [EmployeeController::class, 'update']);
Route::post('/deleteemployee/{id}', [EmployeeController::class, 'destroy']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::get('/profile',[ProfileController::class,'profile']);
    Route::group(['prefix'=>'/profile'],function (){
        Route::post('/update',[UpdateProfileController::class,'update_info']);
    });

});
//forget & reset password
Route::post('/forget-password', [ForgetPasswordController::class,'ForgetPassword']);
Route::post('/verify-otp',[ResetPasswordController::class,'verifyOtp']);
Route::post('/update-password',[ResetPasswordController::class,'updatePassword']);
Route::group(['middleware' => ['jwt.verify']], function() {
    //userDashboard
    Route::get('/user-dashboard', [UserDashboardController::class, 'getUserStatistics']);
    //storeVisit
    Route::post('/store-visit',[VisitsController::class,'storeVisit']);

});
//adminDashboard
Route::get('/admin-dashboard', [AdminDashboardController::class, 'getAdminStatistics']);









