<?php

namespace App\Http\Controllers;

use App\BloodDonor;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WebController extends Controller
{
    public function index()
    {
        return view("index");
    }

    public function saveData(Request $request)
    {
        try {
            // Trim submitted values
            /*$first_name = preg_replace("#[^0-9a-zA-Z ]#", "", $request->first_name);
            $last_name = preg_replace("#[^0-9a-zA-Z ]#", "", $request->last_name);
            $blood_type = preg_replace("#[^0-9a-zA-Z ]#", "", $request->blood_type);*/

            $this->validate($request, [
                "first_name" => "bail|required|string",
                "last_name" => "bail|required|string",
                "blood_type" => "bail|required|string",
                "lat" => "bail|required",
                "lng" => "bail|required"
            ]);

            if($blood_donor = BloodDonor::where("latitude", $request->lat)->where("longitude", $request->lng)->first())
            {
                $blood_donor->first_name = $request->first_name;
                $blood_donor->last_name = $request->last_name;
                $blood_donor->blood_type = $request->blood_type;
            } else {
                // Create new instance of blood donor
                $blood_donor = new BloodDonor();
                $blood_donor->first_name = $request->first_name;
                $blood_donor->last_name = $request->last_name;
                $blood_donor->blood_type = $request->blood_type;
                $blood_donor->latitude = $request->lat;
                $blood_donor->longitude = $request->lng;
            }

            // Save blood donor information
            $blood_donor->save();

            return response()->json([
                "message" => "Data saved successfully"
            ], 200);

        } catch (ValidationException $exception)
        {
            return response()->json([
                "message" => $exception->validator->errors()->first()
            ], 500);

        } catch (\Exception $exception)
        {
            return response()->json([
                "message" => $exception->getMessage()
            ], 500);
        }
    }

    public function getDonors($lat = null, $lng = null)
    {
        try {
            $donors = new BloodDonor;

            $donors = $donors->where(function ($lat_query) use ($lat, $lng)
            {
                if($lat)
                {
                    $lat_query->where("latitude", "<=", ($lat + 1))->where("latitude", ">=", ($lat - 1));
                }
            })->get();

            return response()->json([
                "donors" => $donors
            ], 200);

        } catch (\Exception $exception)
        {
            return response()->json([
                "message" => $exception->getMessage()
            ], 500);
        }
    }
}
