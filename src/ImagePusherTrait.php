<?php
namespace Lixweb\ImagePusher;

use Lixweb\Exceptions\UndefinedFormIndexException;
use Lixweb\Exceptions\UndefinedPropertyException;

trait ImagePusherTrait {

    public function image($field_name = null, $property = null)
    {
        if($field_name !== null && $property !== null){
            return ( $this->checkExistence($field_name, $property) ) ?  $_FILES[$field_name][$property] : false; 
        }

        if($field_name !== null) {
            return ( $this->checkExistence($field_name) ) ?  $_FILES[$field_name] : false; 
        }
    }

    public function getImageProperty($field_name,$property)
    {
        return $this->image($field_name, $property); 
    }

    public function checkExistence($field_name = null, $property = null, $log = false)
    {
           if($property !== null && $field_name !== null){
                try {
                    if(isset($_FILES[$field_name][$property])) {
                        return true; 
                    }
                    throw new UndefinedPropertyException; 
                }catch(UndefinedPropertyException $ex){
                   echo  ($log) ? $ex->errorMessage() : null ; 
                }
           }

           if($property == null && $field_name !== null){
                try {
                    if(isset($_FILES[$field_name])) {
                        return true; 
                    }
                    throw new UndefinedFormIndexException;
                } catch (UndefinedFormIndexException $ex) {
                    echo  ($log) ? $ex->errorMessage() : null ;  
                }
           }
    }

    public function validImageFile($log = false)
    {
        if(getimagesize($this->image($this->field_name,"tmp_name"))){
            if($log){
                echo "File is an image";
            }
            return  true;
        }   

        if($log){
            echo "File is not an image"; 
        }
        return false; 
    }

    public function validImageExtension($log = false)
    {
        if(!$this->image_type == ""){
            if(in_array($this->image_type, $this->allowed_types)){
                if($log){
                    echo "This extension is allowed file can be uploaded"; 
                }
                return true; 
            }
                if($log){
                    echo "This extension is not allowed, file cannot be uploaded"; 
                }
                return false; 
        }else{
            if($log){
                echo "No uploaded file was detected in the form submitted"; 
            }
            return false; 
        }
    }

    public function validImageSize($log = false)
    {   
        if($this->image($this->field_name,"size") <= $this->max_image_size){
            if($log){
               echo "Image size is ok"; 
            }
                return  true;
        }   
            if($log){
                echo "Image is too large"; 
            }
            return false;     
    }

    public function fileExist($log = false){
        if(file_exists($this->imageFileName())){
            if($log){
                echo "the file already exists, change file name the";
            }
            return true; 
        }
           if($log){
                echo " file does not exist and upload can proceed"; 
           }
           return  false; 
    }

    public function fileMime()
    {
        return getimagesize($this->image($this->field_name,"tmp_name"))["mime"]; 
    }

    public function getImageType($image_file_path)
    {
        return strtolower(pathinfo($this->imageFilePath($image_file_path),PATHINFO_EXTENSION));
    }

    public function parseStorageDirectory($directory)
    {
        if(!$directory[0] == '/'){
            return $directory; 
        }
        return trim($directory, '/');
    }

    public function imageFilePath($directory = null, $custom_name = null){
        if(!$directory == null){
            return $this->parseStorageDirectory($directory) .'/'. $this->imageFileName($custom_name); 
        }
    }

    public function imageFileType($file_name)
    {
        return strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); 
    }

    public function imageFileName($custom_name = null)
    {   
        if(is_null($custom_name)){   
            return basename($this->image($this->field_name, "name")); 
        }
        return $this->parseImageName($custom_name); 
    }

    public function parseImageName($custom_name){
        $ext = explode("/",$this->fileMime())[1]; 
        return $custom_name . '.' .$ext;  
    }

    public function uploadStatus($status = null)
    {   
        if(!$status == null){
            return $this->upload_status = $status; 
        } 
        
        return $this->upload_status;
    }

    public function formField($field_name = null)
    {
        if(!$field_name == null){
            return $field_name; 
        }
        return $field_name; 
    }

    public function dd($data)
    {
        die(var_dump($data));
    }
}