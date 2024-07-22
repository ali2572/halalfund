<?php

namespace App\Http\Controllers\Api;

use App\helper\handling;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\RestFullApi\ApiResponseBulder;
use App\RestFullApi\Facades\ApiResponse;
use App\services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    public function __construct(private ArticleService $articleService) {
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = $this->articleService->getAllArticles();

        if($result->status){
            return ApiResponse::add_Message($result->data->message)->add_data((object)[
                "articles"=> $result->data->articles             
            ])->get()->response();
        }
        //return json response
        return ApiResponse::add_data($result->data)->add_statusCode(500)->get()->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        //validate request Data
        $validateData=$request->validated();
        //create new article with userId Authenticated user
        $result=$this->articleService->storeArticle($validateData);
        //send success response 
        if($result->status){
            return ApiResponse::add_Message($result->data)->add_statusCode(201)->get()->response();
        }else{
            return ApiResponse::add_data($result->data)->add_statusCode(500)->get()->response();
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    { 
        $result=$this->articleService->showArticle($request->article_id);
        if($result->status=='true'){
            return ApiResponse::add_Message($result->data->message)->add_data($result->data->article)->get()->response();
        }elseif($result->status == 'not-access'){
            return $this->NotAccessHandle();
        }elseif($result->status == 'not-found'){
            return $this->NotFoundIDHandle();
        }else{
            return ApiResponse::add_data($result->data)->add_statusCode(500)->get()->response();
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request)
    {

        $validateData=$request->validated();
        $result=$this->articleService->updateArticle($request->article_id,$validateData);
        if($result->status== 'true'){
            return ApiResponse::add_Message( $result->data)->get()->response();
        }elseif($result->status== 'not-access'){
            return $this->NotAccessHandle();
        }elseif($result->status== 'not-found'){
            return $this->NotFoundIDHandle() ;
        }else{
            return ApiResponse::add_data($result->data)->add_statusCode(500)->get()->response();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $result=$this->articleService->destroyArticle($request->article_id);
        if($result->status== 'true'){
            return ApiResponse::add_Message( $result->data)->get()->response();
        }elseif($result->status== 'not-access'){
            return $this->NotAccessHandle();
        }elseif($result->status== 'not-found'){
            return $this->NotFoundIDHandle();
        }else{
             return ApiResponse::add_data($result->data)->add_statusCode(500)->get()->response();            
        }
        
    }

}
