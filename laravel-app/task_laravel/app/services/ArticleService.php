<?php

namespace  App\services;

use App\Http\Requests\ArticleRequest;
use App\Models\User;
use App\Models\Article;
use Gate;
use Illuminate\Http\Request;


class ArticleService{
    public function getAllArticles()
    {
        try{
        //find articles with relation
        $articles=auth()->user()->articles()->get();
        $result=[];
        //save filter article data
        foreach ($articles as $article){
            $value=$this->filterArticleValue($article);
            array_push($result,$value);
        }
        //return json response
        return [
           "status" => true,
            "data"=>(object)[
                "message" => "articles data",
                "articles"=> $result             
            ] 
        ];        
        }catch(\Exception $e){
            return [
                "status"=> false,
                "data"=> $e->getMessage()
                ];
        }
    } 

    public function storeArticle(array $validateData)
    {
        try{
        //create new article with userId Authenticated user
        auth()->user()->articles()->create([
            "title"=> $validateData['title'],
            "body"=> $validateData['body']
        ]);
        //send success response 
        return [
            "status" => true,
            "data" => "article is stored successfully",
        ];
        }catch(\Exception $e){
            return [    
                "status"=> false,
                "data"=> $e->getMessage()
                ];
        }
    }

    public function showArticle($article_id)
    {   
        try{
            //find Article from request
            $article=Article::find($article_id);
            //check is article have in DB
            if($article){
                //check is article writer is Authenticated user
                if(Gate::allows("view", $article)){
                        //filter data for use in response
                        $value=$this->filterArticleValue($article);
                        //send success response
                        return [
                            "status"=> 'ok',
                            "data"=>(object)[
                                "article"=>$value,
                                "message"=> "article data"
                            ]
                        ];
                }else{
                        //send unsuccess response Forbidden 
                        return [
                            "status"=> 'not-access',
                            "data"=> "you can not show this article",
                        ];
                }
            }else{
                        return [
                            "status"=> 'not-found',
                            'data'=>'',
                        ];
            } 
        }catch(\Exception $e){
            return [    
                'status'=> false,
                'data'=> $e->getMessage()
                ];
        }   
    }

    public function updateArticle($article_id,array $validateData)
    {
      try{
            //find Article from request
            $article=Article::find($article_id);
            //check is article have in DB
            if($article){
                //check is article writer is Authenticated user
                if(Gate::allows("update", $article)){
                    //update article with new date 
                    $article->update($validateData);
                    $article->save();
                    //send success response
                    return [
                        "status"=> 'true',
                        "data"=> "article updated successfully",
                    ];
                }else{
                        return [
                            "status"=> 'not-access',
                            "data"=> "you can not update this article",
                        ];
                }
            }else{
                    return [
                        "status"=> 'not-found',
                        "data"=> "",
                    ] ;
            };
      }catch(\Exception $e){
        return [
            'status'=> false,
            'data'=> $e->getMessage()
        ];
      }
    }

    public function destroyArticle($article_id)
    {
        $article=Article::find($article_id);
        if($article){
            if(Gate::allows("delete", $article)){
                //delete article from DB
                $article->delete();
                return[
                    "status"=> 'true',
                    "data"=> "article removed successfully",
                ];
            }else{
                return [
                    "status"=> 'not-access',
                    "data"=> "you can not remove this article",
                ];
            }
        }else{      
            return [
                "status"=> 'not-found',
                "data"=> "you can not remove this article",
            ];
        };
        
    }
    public function filterArticleValue($article){
        return (object)[
            "article_id"=>(int)$article->id,
            "article_title"=>$article->title,
            "article_body"=>$article->body,
            "article_storeTime"=>$article->created_at,
            "article_writer_name"=>auth()->user()->name
        ];
    }
}

