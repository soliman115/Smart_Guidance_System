<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiControllers\PlaceController;
use App\Http\Controllers\ApiControllers\RegionController;
use App\Http\Controllers\ApiControllers\RouteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiControllers\EmployeeController;
use App\Http\Controllers\ApiControllers\ServiceController;
use App\Http\Controllers\ApiControllers\BuildingController;

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


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/check-code',[AuthController::class,'check_code']);
Route::post('/new-password',[AuthController::class,'new_password']);

Route::get('send-mail', function () {
    
});


Route::group(['prefix'=>'/profile'],function (){
    Route::post('/update',[UserController::class,'update_info']);
});

// place routes
Route::get('/places', [PlaceController::class, 'getPlaces']);
Route::post('/place', [PlaceController::class, 'insertPlace']);
Route::put('/updateplace/{id}', [PlaceController::class, 'updatePlace']);
Route::delete('/deleteplace/{id}', [PlaceController::class, 'deletePlace']);

Route::get('/routes', [RouteController::class, 'getRoutes']);
Route::post('/route', [RouteController::class, 'insertRoute']);
Route::put('/updateRoute/{id}', [RouteController::class, 'updateRoute']);
Route::delete('/deleteRoute/{id}', [RouteController::class, 'deleteRoute']);

Route::post('/region', [RegionController::class, 'insertRegion']);
Route::get('/region', [RegionController::class, 'getRegions']);
Route::put('/updateRegion/{id}', [RegionController::class, 'updateRegion']);
Route::delete('/deleteRegion/{id}', [RegionController::class, 'deleteRegion']);
Route::get('/getRoute/{source}/{Destination}', [RouteController::class, 'getBestPass']);

Route::get('buildings', [BuildingController::class, 'index']);
Route::get('/buildings/{id}', [BuildingController::class, 'show']);
Route::post('buildings', [BuildingController::class, 'store']);
Route::post('buildings/{id}', [BuildingController::class, 'update']);
Route::post('/building/{id}', [BuildingController::class, 'destroy']);

Route::get('services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::post('services', [ServiceController::class, 'store']);
Route::post('services/{id}', [ServiceController::class, 'update']);
Route::post('/service/{id}', [ServiceController::class, 'destroy']);

Route::get('employees', [EmployeeController::class, 'index']);
Route::get('/employees/{id}', [EmployeeController::class, 'show']);
Route::post('employees', [EmployeeController::class, 'store']);
Route::post('employees/{id}', [EmployeeController::class, 'update']);
Route::post('/employee/{id}', [EmployeeController::class, 'destroy']);


