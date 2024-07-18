<?php


use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

    it("test_profile_api_success", function () 
    {
        $user=CreateUser();
        
        $token=auth()->attempt([
        "email"=> $user->email,
        "password"=> "123456789" //defualtPass
        ]);
        $response = $this->get("/api/user/profile",[
            "Accept"=>"application/json",
            "Authorization"=> "Bearer ".$token
        ]);

        $response->assertStatus(200);
        $response->assertJson([  "data"=>[
            "user_id"=> $user->id,
            "user_name"=> $user->name,
            "user_email"=>$user->email
        ]]);
    });
    it("test_profile_api_Unsuccess_invalid_or_not_token", function () 
    {
        $response = $this->get("/api/user/profile",[
            "Accept"=>"application/json"
        ]);

        $response->assertStatus(401);
        $response->assertJson(["message" => "Unauthenticated."]);
    });

 



