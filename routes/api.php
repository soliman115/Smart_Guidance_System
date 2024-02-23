<?php

use App\Http\Controllers\ApiControllers\AdminDashboardController;
use App\Http\Controllers\ApiControllers\VisitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiControllers\PlaceController;
use App\Http\Controllers\ApiControllers\RegionController;
use App\Http\Controllers\ApiControllers\RouteController;
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

Route::get('/routes',[RouteController::class,'getRoutes']);
Route::post('/route',[RouteController::class,'insertRoute']);
Route::put('/updateRoute/{id}',[RouteController::class,'updateRoute']);
Route::delete('/deleteRoute/{id}',[RouteController::class,'deleteRoute']);

Route::post('/region',[RegionController::class,'insertRegion']);
Route::get('/region',[RegionController::class,'getRegions']);
Route::put('/updateRegion/{id}',[RegionController::class,'updateRegion']);
Route::delete('/deleteRegion/{id}',[RegionController::class,'deleteRegion']);

Route::get('/getRoute/{source}/{Destination}', [RouteController::class,'getBestPass']);

// dashboard routes
Route::post('/visits', [VisitController::class,'store']);
Route::get('/dashboard-data', [AdminDashboardController::class,'getStatistics']);
