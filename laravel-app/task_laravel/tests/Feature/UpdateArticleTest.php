<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


    /**
     * A basic feature test example.
     */
    it(" test_update_api_success_article",function(){
        $user=CreateUser();
        
        $article=CreateArticle($user);
         
        $token=auth()->attempt([
            "email"=> $user->email,
            "password"=> "123456789",
        ]);
      
        $response = $this->put("/api/admin/articles/update",[
            "article_id"=> $article->id,
            "title"=>fake()->text(20),
        "body"=>fake()->text(200)
        ],[
            "Accept"=>"application/json",
            "Authorization"=> "Bearer ".$token
        ]);
    
        $response->assertJson([
            "status"=> true,
            "message"=> "article updated successfully"
        ]);
        $response->assertStatus(200);
    });
    it(" test_update_api_Unsuccess_article_validatoin_data",function(){
        $user=CreateUser();
        
        $article=CreateArticle($user);
         
        $token=auth()->attempt([
            "email"=> $user->email,
            "password"=> "123456789",
        ]);
      
        $response = $this->put("/api/admin/articles/update",[
            "article_id"=> $article->id,
            "title"=>'',
        "body"=>2254221545
        ],[
            "Accept"=>"application/json",
            "Authorization"=> "Bearer ".$token
        ]);
    
        $response->assertStatus(422);
    });
    it(" test_update_api_success_article_not_Allow_access",function(){
        $user=CreateUser();
        $token=auth()->attempt([
            "email"=> $user->email,
            "password"=> '123456789']);
        $user2=CreateUser();
        $article=$user2->articles()->save(Article::factory()->make());

        $response = $this->put("/api/admin/articles/update",[
            "article_id"=> $article->id,
            "title"=>fake()->text(20),
        "body"=>fake()->text(200)
        ],[
            "Accept"=>"application/json",
            "Authorization"=> "Bearer ".$token
        ]);
    
        $response->assertStatus(403);
    });
    it(" test_update_api_success_article_not_currect_article_id",function(){
        $user=CreateUser();
        $token=auth()->attempt([
            "email"=> $user->email,
            "password"=> '123456789']);

            $response = $this->put("/api/admin/articles/update",[
                "article_id"=> '',
                "title"=>fake()->text(20),
                "body"=>fake()->text(200)
            ],[
                "Accept"=>"application/json",
                "Authorization"=>"Bearer ".$token
            ]);
        
        $response->assertStatus(404);
    });
