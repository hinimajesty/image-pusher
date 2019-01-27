<?php
namespace Lixweb\ImagePusher; 

use Lixweb\Generators\RandomCodeGenerator;
use Lixweb\ImagePusher\ImagePusherInterface;
use Lixweb\ImagePusher\ImagePusherTrait;

class ImagePusher implements ImagePusherInterface {

    use ImagePusherTrait; 

    private const IMAGE_TOO_LARGE      = 001; 
    private const IMAGE_NOT_UPLOADED   = 002;
    private const IMAGE_NAME_EXISTS    = 003; 
    private const INVALID_EXTENSION    = 004; 

    private $field_name        = null; 
    private $upload_status     = false;
    private $directory         = null; 
    private $image_type        = null;  
    private $max_image_size    = null; 
    public  $proceed_flag       = false; 
    private $allowed_types     = ['jpg','png','jpeg','gif']; 


    public function __construct($field_name, $directory, $max_image_size = 10000000){
        if( $this->checkExistence($field_name) ){
            $this->field_name =  $this->formField($field_name);
            $this->directory  = $this->parseStorageDirectory($directory); 
            $this->image_type = $this->getImageType($directory);  
            $this->max_image_size = $max_image_size;   
            
            $this->proceed_flag = true; 
        }else{
           // echo $this->parseErrCode( self::IMAGE_NOT_UPLOADED ); 
        }
        return $this; 
    }

    public function save(){
        $this->storeImage(); 
        return $this; 
    }

    private function storeImage($file_name = null)
    {
        if( ! is_numeric( $this->processImage() ) ){
            if(is_null($file_name)){
                if(move_uploaded_file($this->image($this->field_name,"tmp_name"), $this->imageFilePath($this->directory))){
                    echo "Upload Succeeded";
                }else{
                    echo "Upload did not succeed"; 
                }
            }else {
                if(move_uploaded_file(
                        $this->image($this->field_name,"tmp_name"),
                        $this->imageFilePath($this->directory, $file_name)
                )){
                    echo "Upload Succeeded";
                }else{
                    echo "Upload did not succeed"; 
                }
            }
        
        }else{
            $this->parseErrCode($this->processImage());
        }
    }


    public function saveWithRandom()
    {
        $this->saveAs(RandomCodeGenerator::unique());
    }

    public function saveAs($file_name = null){
        if(!is_null($file_name)){
            $this->storeImage($file_name); 
        }else{
            echo "No file name provided, use Save() method if this is a prefered approach"; 
        }
        return $this; 
    }

    private function processImage($log = false)
    {
        $processing_error = null; 

        if($this->proceed_flag){
            if(!$this->fileExist()){
                if($this->validImageSize()){
                    if($this->validImageExtension()){ 
                        if($log){
                            echo "Image processed and ready for saving"; 
                        }
                        return true;
                    }else { return self::INVALID_EXTENSION; }
                }else { return self::IMAGE_TOO_LARGE; }
            }else { return self::IMAGE_NAME_EXISTS; }
        }else{
            return self::IMAGE_NOT_UPLOADED; 
        }
    }

    private function parseErrCode($code){
        switch($code) {
            case self::IMAGE_TOO_LARGE: 
                echo "Image too large to upload halted."; 
            break;
            case self::IMAGE_NOT_UPLOADED:
                echo "Image not uploaded yet."; 
            break;
            case self::INVALID_EXTENSION: 
                echo "Image extension is invalid."; 
            break; 
            case self::IMAGE_NAME_EXISTS: 
                echo "Image name already exists."; 
            break; 
        }
    }
}

