<?php

namespace  App\services;

use App\base\ServiceReturn;
use App\base\serviceWrapper;
use App\Models\User;
use Illuminate\Http\Request;


class UserService{

    public function registerUser(array $valadateData):ServiceReturn
    {
        //use serviceWrapper for lessRepetCode
        return app(serviceWrapper::class)(function()use($valadateData){
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
            return new ServiceReturn((object)[
                "token"=>$token,
                "expires_in" => auth()->factory()->getTTL() * 60
                ],true);
        });
    }
    public function loginUser(Request $request){
        return app(serviceWrapper::class)(function()use($request){
            $token = auth()->attempt([
                "email" => $request->email,
                "password" => $request->password
            ]);
            if($token){
                return new ServiceReturn((object)[
                    "token"=>$token,
                    "expires_in" => auth()->factory()->getTTL() * 60
                    ],'haveToken');
                }
                return new ServiceReturn("email or password was wrong",'notToken');
        });     
    }
    public function ProfileUser(){
        return app(serviceWrapper::class)(function(){
            $user=auth()->user();
            return new ServiceReturn($this->filterUserDetails($user),true);
        });
    }
    public function refreshTokenUser(){
        
        return app(serviceWrapper::class)(function(){
            $token = auth()->refresh();
            return new ServiceReturn((object)[
            "token"=>$token,
            "expires_in" => auth()->factory()->getTTL() * 60],
            true
            );
        });

    }
    public function logoutUser(){
      return app(serviceWrapper::class)(function(){
        auth()->logout();
        return new ServiceReturn("User logged out",
            true
            );
      });
    }
    
    public function filterUserDetails($user){
        return [
              "user_id"=> $user->id,
              "user_name"=> $user->name,
              "user_email"=> $user->email,
        ];
      }
  
}

