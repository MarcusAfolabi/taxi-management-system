<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Events\CarAdded;
use App\Events\CarDeleted;
use App\Events\CarUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Car::all(), 200);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 422);

        }
    }

    public function store(Request $request)
    {
        try {
            // Define validation rules
            $validatedData = $request->validate([
                'car_name' => 'required|string|max:255|unique:cars',
                'model' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'availability_status' => 'required|boolean',
            ]);

            // Create the car using validated data
            $car = Car::create($validatedData);
            broadcast(new CarAdded($car))->toOthers();

            // Return success response
            return response()->json($car, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            // Log the error for debugging
            Log::error($th->getMessage());

            // Return a general error response
            return response()->json(['error' => 'An error occurred while saving the car.'], 500);
        }
    }


    public function show($id)
    {
        try {
            $car = Car::find($id);
            return $car ? response()->json($car) : response()->json(['message' => 'Car not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 422);
        }
    }

    public function update(Request $request, $id)
    {
        $car = Car::find($id);
        if ($car) {
            $car->update($request->all());
            broadcast(new CarUpdated($car))->toOthers();
            return response()->json($car, 200);
        }
        return response()->json(['message' => 'Car not found'], 404);
    }

    public function destroy($id)
    {
        $car = Car::find($id);
        if ($car) {
            $car->delete();
            broadcast(new CarDeleted($car))->toOthers();
            return response()->json(['message' => 'Car deleted'], 200);
        }
        return response()->json(['message' => 'Car not found'], 404);
    }
}
