<?php

namespace App\base;
 
class serviceWrapper{

public function __invoke(\Closure $body,\Closure $exep=null){
    try{
        return $body();
    }catch(\Exception $e){
        !is_null($ex) && $exep();
        return new ServiceReturn($e->getMessage(),false);  
    }
    
}
}