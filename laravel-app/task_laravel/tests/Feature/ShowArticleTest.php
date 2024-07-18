<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


    /**
     * A basic feature test example.
     */
    it(" test_show_api_success_article",function(){
        $user=CreateUser();
        
        $article=CreateArticle($user);
         
        $token=auth()->attempt([
            "email"=> $user->email,
            "password"=> "123456789",
        ]);
      
        $response = $this->post("/api/admin/articles/show",["article_id"=> $article->id],[
            "Accept"=>"application/json",
            "Authorization"=> "Bearer ".$token
        ]);
    
        $response->assertJson([
            "status"=> true,
            "message"=> "article data"
        ]);
    });
    it(" test_show_api_success_article_not_Allow_access",function(){
        $user=CreateUser();
        $token=auth()->attempt([
            "email"=> $user->email,
            "password"=> '123456789']);
        $user2=CreateUser();
        $article=$user2->articles()->save(Article::factory()->make());

        $response = $this->post("/api/admin/articles/show",["article_id"=> $article->id],[
            "Accept"=>"application/json",
            "Authorization"=> "Bearer ".$token
        ]);
        $response->assertStatus(403);
    });
    it(" test_show_api_success_article_not_currect_article_id",function(){
        $user=CreateUser();
        $token=auth()->attempt([
            "email"=> $user->email,
            "password"=> '123456789']);

        $response = $this->post("/api/admin/articles/show",["article_id"=> ""],[
            "Accept"=>"application/json",
            "Authorization"=> "Bearer ".$token
        ]);
        $response->assertStatus(404);
    });
    

