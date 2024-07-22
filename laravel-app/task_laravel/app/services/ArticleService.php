<?php

namespace  App\services;

use App\base\ServiceReturn;
use App\base\serviceWrapper;
use App\Http\Requests\ArticleRequest;
use App\Models\User;
use App\Models\Article;
use Gate;
use Illuminate\Http\Request;


class ArticleService{
    public function getAllArticles()
    {
         return app(serviceWrapper::class)(function(){
            
            //find articles with relation
            $articles=auth()->user()->articles()->get();
            
            $result=[];
            //save filter article data
            foreach ($articles as $article){
                $value=$this->filterArticleValue($article);
                array_push($result,$value);
            }

            return new ServiceReturn((object)[
                "message" => "articles data",
                "articles"=> $result],
                true
            );
         });
    } 

    public function storeArticle(array $validateData)
    {
        return app(serviceWrapper::class)(function()use($validateData){
            //create new article with userId Authenticated user
            auth()->user()->articles()->create([
                "title"=> $validateData['title'],
                "body"=> $validateData['body']
            ]);
            return new ServiceReturn("article is stored successfully",true);
        });
        
    
    }

    public function showArticle($article_id)
    {   
        return app(serviceWrapper::class)(function()use($article_id){
            //find Article from request
            $article=Article::find($article_id);
            //check is article have in DB
            if($article){
                //check is article writer is Authenticated user
                if(Gate::allows("view", $article)){
                        //filter data for use in response
                        $value=$this->filterArticleValue($article);
                        
                        return new ServiceReturn((object)[
                            "article"=>$value,
                            "message"=> "article data"
                        ],'true');
                }else{
                    return new ServiceReturn("you can not show this article",'not-access'); 
                }
            }else{
                return new ServiceReturn("",'not-found'); 
            }
        });
    }

    public function updateArticle($article_id,array $validateData)
    {
     return app(serviceWrapper::class)(function()use($article_id, $validateData){
        //find Article from request
        $article=Article::find($article_id);
        //check is article have in DB
        if($article){
            //check is article writer is Authenticated user
            if(Gate::allows("update", $article)){
                //update article with new date 
                $article->update($validateData);
                $article->save();
                return new ServiceReturn("article updated successfully",'true');
            
            }else{
                return new ServiceReturn("you can not show this article",'not-access'); 
            }
        }else{
            return new ServiceReturn("",'not-found'); 
        };
     });
    }

    public function destroyArticle($article_id)
    {
        return app(serviceWrapper::class)(function()use($article_id){
            $article=Article::find($article_id);
            if($article){
                if(Gate::allows("delete", $article)){
                    //delete article from DB
                    $article->delete();
                    return new ServiceReturn("article removed successfully",'true');
                }else{
                    return new ServiceReturn("you can not show this article",'not-access'); 
                }
            }else{      
                return new ServiceReturn("",'not-found'); 
            };
        });
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

