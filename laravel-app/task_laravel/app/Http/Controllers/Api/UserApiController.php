<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\RestFullApi\Facades\ApiResponse;
use App\RestFullApi\ApiResponseBulder;
use App\services\UserService;
use Illuminate\Http\Request;
use App\Models\User;

class UserApiController extends Controller
{

    public function __construct(private UserService $userService) {
        
    }
    // Register API - POST (name, email, password)
    public function register(UserRequest $request){

        // Validation
        $valadateData=$request->validated();

        // User model to save user in database
        $result=$this->userService->registerUser($valadateData);

        if($result['status']){
        //send token and expire response 
        return ApiResponse::add_Message("User registered successfully")->add_append([
            "token" => $result['data']->token,
            "expires_in" => $result['data']->expires_in
        ])->add_statocCode(201)->add_status(true)->get()->response();  
        }
        else{
            return ApiResponse::add_data($result['data'])->add_statocCode(500)->get()->response();
        }
       

    }

    // Login API - POST (email, password)
    public function login(Request $request){

        // Validation requset body
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        
        // Authentication user and generate token
        $result=$this->userService->loginUser($request);
        //check if Authentication user is faild
        if($result['status']=='notToken'){
            return ApiResponse::add_Message("email or password was wrong")->add_statocCode(401)->add_status(false)->get()->response();
        }
        elseif($result['status'] =='haveToken'){
            return ApiResponse::add_Message("User logged in")->add_append([
                "token" => $result['data']->token,
                "expires_in" => $result['data']->expires_in
            ])->add_status(true)->get()->response();  
        }else{
            return ApiResponse::add_data($result['data'])->add_statocCode(500)->get()->response();
        }

    }

    // Profile API - GET (JWT Auth Token)
    public function profile(){
        //set usereData from UserService 
        $result=$this->userService->ProfileUser();

        if($result['status']){
            return ApiResponse::add_Message("Profile data")->add_data($result['data'])->get()->response();
        }else{
            return ApiResponse::add_data($result['data'])->add_statocCode(500)->get()->response();
        }
    }


    
    // Refresh Token API - GET (JWT Auth Token)
    public function refreshToken(){

        $result=$this->userService->refreshTokenUser();
        if($result['status']){
            return ApiResponse::add_Message("Refresh token",)->add_append([
                "token" => $result['data']->token,
                "expires_in" => $result['data']->expires_in  
            ])->get()->response();
        }else{
            return ApiResponse::add_data($result['data'])->add_statocCode(500)->get()->response();
        }
    }

    // Logout API - GET (JWT Auth Token)
    public function logout(){
        
        $result=$this->userService->logoutUser();
        if($result['status']){
            return ApiResponse::add_Message($result['data'])->get()->response();
        }else{
            return ApiResponse::add_data($result['data'])->add_statocCode(500)->get()->response();
        }
    }
}