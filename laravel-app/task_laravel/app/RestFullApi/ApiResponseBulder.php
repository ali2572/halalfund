<?php

namespace App\RestFullApi;

class ApiResponseBulder{

    private ApiResponse $response;
    public function __construct() {
        $this->response = new ApiResponse();
    }

    public function add_Message(string $message){
        $this->response->set_Massage($message);
        return $this;
    }
    public function add_data(mixed $data){
        $this->response->set_data($data);
        return $this;
    }
    public function add_statusCode(int $statocCode){
        $this->response->set_statusCode($statocCode);
        return $this;
    }
    public function add_append(array $append){
        $this->response->set_append($append);
        return $this;
    }
    public function add_status(bool $status){
        $this->response->set_status($status);
        return $this;
    }

    public function get(): ApiResponse
    {
        return $this->response;
    }

}