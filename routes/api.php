<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiControllers\PlaceController;
use App\Http\Controllers\ApiControllers\RegionController;
use App\Http\Controllers\ApiControllers\RouteController;
use App\Http\Controllers\ApiControllers\EmployeeController;
use App\Http\Controllers\ApiControllers\ServiceController;
use App\Http\Controllers\ApiControllers\BuildingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

//CURD buildings
Route::get('buildings', [BuildingController::class, 'index']);
Route::get('/buildings/{id}', [BuildingController::class, 'show']);
Route::post('buildings', [BuildingController::class, 'store']);
Route::post('buildings/{id}', [BuildingController::class, 'update']);
Route::post('/building/{id}', [BuildingController::class, 'destroy']);
//CURD services
Route::get('services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::post('services', [ServiceController::class, 'store']);
Route::post('services/{id}', [ServiceController::class, 'update']);
Route::post('/service/{id}', [ServiceController::class, 'destroy']);

//get service by place
Route::post('/servicestoplace/{id}', [ServiceController::class, 'getservicebyplace']);

//CURD employees
Route::get('employees', [EmployeeController::class, 'index']);
Route::get('/employees/{id}', [EmployeeController::class, 'show']);
Route::post('employees', [EmployeeController::class, 'store']);
Route::post('employees/{id}', [EmployeeController::class, 'update']);
Route::post('/employee/{id}', [EmployeeController::class, 'destroy']);


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/check-code',[AuthController::class,'check_code']);
Route::post('/new-password',[AuthController::class,'new_password']);

Route::get('send-mail', function () {

});


Route::group(['prefix'=>'/profile'],function (){
    Route::post('/update',[UserController::class,'update_info']);
});
