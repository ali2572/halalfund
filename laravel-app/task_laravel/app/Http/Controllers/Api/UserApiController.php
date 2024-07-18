<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;

class UserApiController extends Controller
{
    // Register API - POST (name, email, password)
    public function register(UserRequest $request){

        // Validation
        $valadateData=$request->validated();

        // User model to save user in database
        User::create([
            "name" => $valadateData['name'],
            "email" => $valadateData['email'],
            "password" => bcrypt($valadateData['password'])
        ]);
        // Authentication user and generate token
        $token = auth()->attempt([
            "email" => $valadateData['email'],
            "password" => $valadateData['password']
        ]);
        //send token and expire response 
        return response()->json([
            "status" => true,
            "message" => "User registered successfully",
            "token" => $token,
            "expires_in" => auth()->factory()->getTTL() * 60
        ],201);        
    }

    // Login API - POST (email, password)
    public function login(Request $request){

        // Validation requset body
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        // Authentication user and generate token
        $token = auth()->attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);
        //check if Authentication user is faild
        if(!$token){

            return response()->json([
                "status" => false,
                "message" => "email or password was wrong"
            ],401);
        }

        return response()->json([
            "status" => true,
            "message" => "User logged in",
            "token" => $token,
            "expires_in" => auth()->factory()->getTTL() * 60
        ]);

    }

    // Profile API - GET (JWT Auth Token)
    public function profile(){
        //set usereData from auth method
        $userData = auth()->user();
    
        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "data"=>$this->filterUserDetails($userData) //method for filter user data
        ]);
    }

    //filter user data
    public function filterUserDetails($user){
      return [
            "user_id"=> $user->id,
            "user_name"=> $user->name,
            "user_email"=> $user->email,
      ];
    }

    // Refresh Token API - GET (JWT Auth Token)
    public function refreshToken(){

        $token = auth()->refresh();

        return response()->json([
            "status" => true,
            "message" => "Refresh token",
            "token" => $token,
            "expires_in" => auth()->factory()->getTTL() * 60
        ]);
    }

    // Logout API - GET (JWT Auth Token)
    public function logout(){
        
        auth()->logout();

        return response()->json([
            "status" => true,
            "message" => "User logged out"
        ]);
    }
}