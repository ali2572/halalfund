<?php

namespace  App\services;

use App\Models\User;
use Illuminate\Http\Request;


class UserService{

    public function registerUser(array $valadateData){
        try{
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

        }catch(\Exception $e){
            return [
                'status'=>false,
                'data'=>$e->getMessage()
            ];  
        }
        return [
            'status'=>true,
            'data'=>(object)[
                "token"=>$token,
                "expires_in" => auth()->factory()->getTTL() * 60
                ]
        ];
    }
    public function loginUser(Request $request){
        try{

            $token = auth()->attempt([
                "email" => $request->email,
                "password" => $request->password
            ]);
            if($token){
                return [    
                    "status"=>'haveToken',
                    'data'=>(object)[
                        "token"=>$token,
                        "expires_in" => auth()->factory()->getTTL() * 60
                        ]
                    ];
                }
                return[
                    "status"=>'notToken',
                    "data"=> "email or password was wrong"
                    ];
        }catch(\Exception $e){
            return [
                'ok'=>false,
                'data'=>$e->getMessage()
                ];
            }      
    }
    public function ProfileUser(){
      try{
        $user=auth()->user();
        return [
            'status'=>true,
            'data'=>$this->filterUserDetails($user)
            ];
        }catch(\Exception $e){
            return [    
                'status'=>false,
                'data'=>$e->getMessage()
                ];
        }

    }
    public function refreshTokenUser(){
        try{
        $token = auth()->refresh();

        return [
            "status" => true,
            "data" => (object)[
            "token"=>$token,
            "expires_in" => auth()->factory()->getTTL() * 60
            ]
        ];
        
        }catch(\Exception $e){
            return [
                "status"=>false,
                "data"=>$e->getMessage()
                ];
        }
    }
    public function logoutUser(){
        try{
        auth()->logout();

        return[
            "status" => true,
            "data" => "User logged out"
        ];
        }
        catch(\Exception $e){
            return [
                "status"=>false,
                "data"=>$e->getMessage()
                ];
        }
    }
    
    public function filterUserDetails($user){
        return [
              "user_id"=> $user->id,
              "user_name"=> $user->name,
              "user_email"=> $user->email,
        ];
      }
  
}

