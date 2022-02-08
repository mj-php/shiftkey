<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\TripController;
use App\Http\Resources\TripCollection;
use App\Trip;
use Illuminate\Http\Request;
use App\Http\Resources\CarCollection;
use App\Car;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


//////////////////////////////////////////////////////////////////////////
/// Mock Endpoints To Be Replaced With RESTful API.
/// - API implementation needs to return data in the format seen below.
/// - Post data will be in the format seen below.
/// - /resource/assets/traxAPI.js will have to be updated to align with
///   the API implementation
//////////////////////////////////////////////////////////////////////////

// Mock endpoint to get all cars for the logged in user

Route::get('/get-cars', function (Request $request) {
    $loggedUserId = Auth::user()->getAuthIdentifier();
    return new CarCollection(Car::where('user_id', $loggedUserId)->get());
})->middleware('auth:api');


// Mock endpoint to add a new car.

Route::post('add-car', [CarController::class, 'store'])->middleware(['auth:api']);


// Mock endpoint to get a car with the given id

Route::get('/get-car/{id}', function (Request $request, $id) {
    return response()->json(['data' => Car::find($id)]);
})->middleware('auth:api');


// Mock endpoint to delete a car with a given id

Route::delete('delete-car/{id}', [CarController::class, 'destroy'])->middleware('auth:api');


// Mock endpoint to get the trips for the logged in user

Route::get('/get-trips', function (Request $request) {

    $loggedUserId = Auth::user()->getAuthIdentifier();

    return new TripCollection(Trip::with('car')
        ->whereHas('car', function ($query) use ($loggedUserId) {
            $query->where('user_id', $loggedUserId);
        })
        ->get());

})->middleware('auth:api');


// Mock endpoint to add a new trip.

Route::post('add-trip', [TripController::class, 'store'])->middleware(['auth:api']);
