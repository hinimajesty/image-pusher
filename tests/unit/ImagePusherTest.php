<?php

use PHPUnit\Framework\TestCase;
use Yhapps\ImagePusher\ImagePusher;

class ImagePusherTest extends TestCase 
{
    private $imagePusher; 

    public function setUp() : void
    {
        parent::setUp(); 

        $this->imagePusher = new ImagePusher('image1.jpg','cat',1000); 
    }

    /** @test */
    public function it_can_create_an_instance_of_image()
    {
        $this->assertTrue($this->imagePusher instanceof ImagePusher); 
    }

    /** @test */
    public function it_uploads_jpg_image()
    {
        $this->assertTrue(in_array('jpg',$this->imagePusher->getAllowedImageTypes()));
    }

    /** @test */
    public function it_uploads_png_image()
    {
        $this->assertTrue(in_array('png',$this->imagePusher->getAllowedImageTypes()));
    }

    /** @test */
    public function it_uploads_jpeg_image()
    {
        $this->assertTrue(in_array('jpeg',$this->imagePusher->getAllowedImageTypes()));
    }

    /** @test */
    public function it_uploads_gif_image()
    {
        $this->assertTrue(in_array('gif',$this->imagePusher->getAllowedImageTypes()));
    }

    /** @test */
    public function image_too_large_err_code_is_100()
    {
        $this->assertSame($this->imagepusher->getImageTooLargeErrorCode, 001);  
    }
}