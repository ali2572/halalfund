<?php

namespace App\Http\Controllers\Api;

use App\helper\handling;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //find articles with relation
        $articles=auth()->user()->articles()->get();
        $result=[];
        //save filter article data
        foreach ($articles as $article){
            $value=(object)[
                "article_id"=>(int)$article->id,
                "article_title"=>$article->title,
                "article_body"=>$article->body,
                "article_storeTime"=>$article->created_at,
                "article_writer_name"=>auth()->user()->name
            ];
            array_push($result,$value);
        }
        //return json response
        return response()->json([
           "status" => true,
            "message" => "articles data",
            "data"=>[
                "articles"=> $result             
            ] 
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        //validate request Data
        $validateData=$request->validated();
        //create new article with userId Authenticated user
        auth()->user()->articles()->create([
            "title"=> $validateData['title'],
            "body"=> $validateData['body']
        ]);
        //send success response 
        return response()->json([
            "status" => true,
            "message" => "article is stored successfully",
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {   
        //find Article from request
        $article=Article::find($request->article_id);
        //check is article have in DB
        if($article){
            
            //check is article writer is Authenticated user
            if(Gate::allows("access-use", $article)){
                    //filter data for use in response
                    $value=[
                        "article_id"=>(int)$article->id,
                        "article_title"=>$article->title,
                        "article_body"=>$article->body,
                        "article_storeTime"=>$article->created_at,
                        "article_writer_name"=>$article->user->name
                    ];
                    //send success response
                    return response()->json([
                        "status"=> true,
                        "message"=> "article data",
                        "data"=>$value
                    ]);
            }else{
                    //send unsuccess response Forbidden 
                    return response()->json([
                        "status"=> false,
                        "message"=> "you can not show this article",
                    ],403);
            }
        }else{
                    return $this->NotFoundIDHandle();
        }    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, Article $article)
    {
        //find Article from request
        $article=Article::find($request->article_id);
        //check is article have in DB
        if($article){
            //check is article writer is Authenticated user
            if(Gate::allows("access-use", $article)){
                //validate new data for update
                $validateData=$request->validated();
                //update article with new date 
                $article->update($validateData);
                $article->save();
                //send success response
                return response()->json([
                    "status"=> true,
                    "message"=> "article updated successfully",
                ]);
            }else{
                    return response()->json([
                        "status"=> false,
                        "message"=> "you can not update this article",
                    ],403);
            }
        }else{
                return $this->NotFoundIDHandle() ;
        };
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $article=Article::find($request->article_id);
        if($article){
            
            if(Gate::allows("access-use", $article)){
                //delete article from DB
                $article->delete();
                return response()->json([
                    "status"=> true,
                    "message"=> "article removed successfully",
                ]);
            }else{
                return response()->json([
                    "status"=> false,
                    "message"=> "you can not remove this article",
                ],403);
            }
        }else{      
                return $this->NotFoundIDHandle() ;
        };
        
    }

}
