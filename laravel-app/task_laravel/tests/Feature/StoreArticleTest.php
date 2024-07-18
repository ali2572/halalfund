<?php


use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

    it(" test_store_api_success_article",function(){
        $user=CreateUser();

        $token=auth()->attempt([    
            "email"=> $user->email,
            "password"=> "123456789", 
        ]);

        $response = $this->post("/api/admin/articles/store",[
            "title"=>fake()->text(20),
            "body"=>fake()->text(200)
        ],[
            "Accept"=>"application/json",
            "Authorization"=> "Bearer ".$token
        ]);
        
            $response->assertStatus(201);
    });
    it(" test_store_api_Unsuccess_article_not_token",function(){

        $response = $this->post("/api/admin/articles/store",[
            "title"=>fake()->text(20),
            "body"=>fake()->text(200),
        ],[
            "Accept"=> "application/json"
        ]);
        $response->assertStatus(401);
    });

    it(" test_store_api_Unsuccess_article_not_unique_title",function(){
        $user=CreateUser();

        $token=auth()->attempt([    
            "email"=> $user->email,
            "password"=> "123456789", 
        ]);


        $article=CreateArticle($user);

        $response = $this->post("/api/admin/articles/store",[
            "title"=>$article->title,
            "body"=>fake()->text(200),
        ],[
            "Accept"=>"application/json",
            "Authorization"=> "Bearer ".$token
        ]);
        $response->assertJson(["message"=> "The title has already been taken."]);
    });

    it(" test_store_api_Unsuccess_article_not_valid_data",function(){

        $user=CreateUser();

        $token=auth()->attempt([    
            "email"=> $user->email,
            "password"=> "123456789", 
        ]);
        //data must be valid for store
        $response = $this->post("/api/admin/articles/store",[
            "title"=>'',
            "body"=>''
        ],[
            "Accept"=>"application/json",
            "Authorization"=> "Bearer ".$token
        ]);
        $response->assertStatus(422);
    }); 

