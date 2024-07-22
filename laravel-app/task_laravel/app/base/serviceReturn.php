<?php

namespace App\base;

class ServiceReturn{

    public function __construct(public mixed $data = null,public mixed $status=null){
    
    }
}