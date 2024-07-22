<?php

namespace App\Http\Controllers;

use App\RestFullApi\Facades\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function NotFoundIDHandle(){
         return ApiResponse::add_Message("this Id is not found")->add_status(false)->add_statusCode(404)->get()->response();
    }
    public function NotAccessHandle(){
        return ApiResponse::add_Message("you can not show this article")->add_status(false)->add_statusCode(403)->get()->response();
    }

}
