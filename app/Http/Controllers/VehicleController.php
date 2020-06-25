<?php

namespace App\Http\Controllers;

use App\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::where('s_datums', '=', null)->get();
        return response()->json(
            [
                'vehicles' => $vehicles
            ],
            200
        );
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'numberPlate' => 'required',
                'columnId' => 'required'
            ]
        );

        $vehicle = new Vehicle;
        $vehicle->number_plate = $request->numberPlate;
        $vehicle->column_id = $request->columnId;
        $vehicle->save();

        return response()->json(
            [
                'vehicle' => $vehicle,
                'message' => 'Vehicle has been added!'
            ]
        );
    }

    public function filter(Request $request)
    {
        $request->validate(
            [
                'datetime' => 'required',
            ]
        );

        $vehicles = Vehicle::where('datums', '>=', $request->datetime)
            ->where($request->datetime, '<=', 's_datums'
        )->get();

        return response()->json(
            [
                'vehicles' => $vehicles,
                'message' => 'Vehicle list has been filtered!'
            ],
            200
        );
    }

    public function populateDb(Request $request)
    {
        $request->validate(
            [
                'data' => 'required',
            ]
        );

        foreach ($request->data as $data) {
            $vehicle = new Vehicle;
            $vehicle->json_id = $data['id'];
            $vehicle->number_plate = $data['rn'];
            $vehicle->column_id = $data['numurs'];
            if ($data['datums']) {
                $vehicle->datums = date('Y-m-d H:i:s', strtotime($data['datums']));
            } else {
                $vehicle->datums = null;
            }
            if ($data['s_datums']) {
                $vehicle->s_datums = date('Y-m-d H:i:s', strtotime($data['s_datums']));
            } else {
                $vehicle->s_datums = null;
            }
            $vehicle->save();
        }
        return response()->json(
            [
                'message' => 'Vehicle data has been imported!'
            ]
        );
    }


    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'numberPlate' => 'required',
                'columnId' => 'required'
            ]
        );
        $vehicle = Vehicle::where('id', '=', $id)->update(
            [
                'number_plate' => $request->numberPlate,
                'column_id' => $request->columnId
            ]
        );

        return response()->json(
            [
                'vehicle' => $vehicle,
                'message' => 'Vehicle data has been Updated!'
            ]
        );
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return response()->json(
            [
                'vehicle' => $vehicle,
                'message' => 'Vehicle has been Deleted!'
            ]
        );
    }
}
