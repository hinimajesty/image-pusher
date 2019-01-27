<?php
namespace Lixweb\Exceptions; 

class UndefinedPropertyException extends \Exception {
    public function errorMessage()
    {
        return $this->getMessage().'The requested image property is undefined'; 
    }
}