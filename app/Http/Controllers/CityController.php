<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\City;
use Exception;

class CityController extends Controller
{
    public function save_city(Request $request)
    {
       try {
        $city = $request->City;
        $state = $request->State;
        $lat = $request->Lat;
        $lng = $request->Lng;

        $validator = Validator::make([
            "city" => $city,
            "state" => $state,
            'lat'=>$lat,
            'lng'=>$lng,
        ], [
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        //if validation fails
        if ($validator->fails()) {
        return response()->json(["validation_error"=>$validator->errors()], 400);
        }

        $city_exists = City::where('city',$city)->where('latitude',$lat)->where('longitude',$lng)->first();

        if (!$city_exists) {
        $city = City::create(["state"=>$state,"city"=>$city,"latitude"=>$lat,"longitude"=>$lng,'user_id'=>$request->user()->email]);
        return response()->json(["message"=>"created successfully","obj"=>$city], 201);
        }
        else{
            return response()->json(["message"=>"city already exists"], 200);
        }
    
       } catch (\Exception $e) {
        return response()->json(["message"=>$e->getMessage()], 400);
       }
        
    }

    public function get_saved_cities(Request $request)
    {
       try {
         //get cities saved by the user
        $saved_cities = City::where('user_id',$request->user()->email)->get();
         if ($saved_cities) {
            return response()->json(["message"=>"success","cities"=>$saved_cities], 200);
         }
         else{
            return response()->json(["message"=>"no city was found"], 404);
         }     
        
       } catch (\Exception $e) {
        return response()->json(["message"=>$e->getMessage()], 400);
       }
        
    }

    public function delete_city(Request $request){
       $city_exists = City::find($request->id);
       
       if ($city_exists) {
         //check if the user saved the city initially
        if ($city_exists->user_id == $request->user()->email) {
           $city_exists->delete();
           return response()->json(["message"=>"success"], 200);
        }
        else {
            return response()->json(["message"=>"Unauthorized"], 401);
        }
       }
       else{
        return response()->json(["message"=>"no city was found"], 404);
       }
    }
}
