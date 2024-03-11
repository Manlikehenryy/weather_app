<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;

class UserController extends Controller
{
    public function register(Request $request)
    {   
      try {
        $validator = Validator::make([
          "email" => $request->Email,
          "password" => $request->Password,
          'firstname'=>$request->Firstname,
          'lastname'=>$request->Lastname,
          'password_confirmation'=>$request->ConfirmPassword
      ], [
          'email' => 'required|string|email|max:255',
          'password' => 'required|string|min:5|confirmed',
          'firstname' => 'required',
          'lastname' => 'required',
      ]);
      
      //if validation fails
      if ($validator->fails()) {
        return response()->json(["validation_error"=>$validator->errors()], 400);
      }

        $user = User::create(['email'=>$request->Email,'password'=>Hash::make($request->Password),
        'firstname'=>$request->Firstname, 'lastname'=>$request->Lastname]);

        //if user was created successfully
        if ($user) {
            $data = ["message"=>"success"];
            return response()->json($data, 200);
        }
        else{
            return response()->json(["message"=>"unsuccessful"], 400);
        }
      } catch (\Exception $e) {
        return response()->json(["message"=>$e->getMessage()], 400);
      }
    }


    public function login(Request $request)
    {   
      try {
        $validator = Validator::make([
          "email" => $request->Email,
          "password" => $request->Password,
      ], [
          'email' => 'required|string|email|max:255',
          'password' => 'required|string',
      ]);

        //if validation fails
        if ($validator->fails()) {
          return response()->json(["validation_error"=>$validator->errors()], 400);
        }
  

       $user = User::where('email',$request->Email)->first();
       
       //identify user
       if (Hash::check($request->Password, $user->password)) {
        $user_browser = $request->Browser;

       //generate unique token for user
       $token = $user->createToken($user_browser)->plainTextToken;
       $data = ['token'=>$token,'message'=>'success','name'=>$user->firstname];
       return response()->json($data, 200);
       }
      } catch (\Exception $e) {
        return response()->json(["message"=>$e->getMessage()], 400);
      }
      
    }  

    public function logout(Request $request){
      $user = User::where('email',$request->user()->email)->first();
      if ($user) {
        //revoke all user's tokens
        $user->tokens()->delete();
        return response()->json(["message"=>"success"], 200);
      }
      else{
        return response()->json(["message"=>"no user was found"], 404);
      }
     
    }
}
