<?php


namespace App\RestFullApi;

class ApiResponse{
 private ?string $message=null;
 private mixed $data=null;
 private int $statusCode=200;
 private array $append=[];
 private bool $status=true;

 public function set_Massage(string $message){
    $this->message=$message;
 }
 public function set_data(mixed $data){
    $this->data=$data;
 }
 public function set_statusCode(int $statusCode){
    $this->statusCode=$statusCode;
 }
 public function set_status(bool $status){
    $this->status=$status;
 }
 public function set_append(array $append){
    $this->append=$append;
 }

 public function response(){
    $body=[];
    !is_null($this->message) && $body['message'] = $this->message;
    !is_null($this->data) && $body['data'] = $this->data;
    $body['status'] = $this->status;
    $body+=$this->append;
    return response()->json($body, $this->statusCode);
 }
}