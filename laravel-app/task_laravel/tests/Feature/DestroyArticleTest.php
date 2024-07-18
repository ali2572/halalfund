<?php


use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

    /**
     * A basic feature test example.
     */
    it("test_destroy_api_success_article" , function () {
        $user=CreateUser();
        
        $article=CreateArticle($user);
         
        $token=auth()->attempt([
            "email"=> $user->email,
            "password"=> "123456789",
        ]);
      
        $response = $this->delete("/api/admin/articles/destroy",[
            "article_id"=> $article->id
        ],[
            "Accept"=>"application/json",
            "Authorization"=> "Bearer ".$token
        ]);
    
        $response->assertJson([
            "status"=> true,
            "message"=> "article removed successfully"
        ]);
        $response->assertStatus(200);
    });

    it("test_destroy_api_success_article_not_Allow_access" , function () {
        $user=CreateUser();
        $token=auth()->attempt([
            "email"=> $user->email,
            "password"=> '123456789']);
        $user2=CreateUser();
        $article=CreateArticle($user2);

        $response = $this->delete("/api/admin/articles/destroy",[
            "article_id"=> $article->id,
        ],[
            "Accept"=>"application/json",
            "Authorization"=> "Bearer ".$token
        ]);
    
        $response->assertStatus(403);
    });  

    it("test_destroy_api_success_article_not_currect_article_id" , function () {
        $user=CreateUser();
        $token=auth()->attempt([
            "email"=> $user->email,
            "password"=> '123456789']);

            $response = $this->delete("/api/admin/articles/destroy",[
                "article_id"=> '',
            ],[
                "Accept"=>"application/json",
                "Authorization"=>"Bearer ".$token
            ]);
        
        $response->assertStatus(404);
    });

