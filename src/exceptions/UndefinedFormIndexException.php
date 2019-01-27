<?php
namespace Lixweb\Exceptions; 

class UndefinedFormIndexException extends \Exception {
    
    public function errorMessage()
    {
        return $this->getMessage().'The form field provided doesnt have an associated set value in the PHP File Superglobal Variable'; 
    }
}