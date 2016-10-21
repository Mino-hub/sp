<?php
$sourse[0]["sourse"]   = "http://i.imgur.com/4L2rYZv.jpg";
$sourse[0]["filename"] = "0009900decca20c822900acdeffea730";
$sourse[1]["sourse"]   = "http://i.imgur.com/9KySaHr.jpg";
$sourse[1]["filename"] = "111dbc9dddda20c822900acdeffea730";
$savepath = "";
$limit_size = 800;
$cq = 40;

foreach ($sourse as $value) {
    $image = new ImageScaleProc($value["sourse"], $value["filename"], $limit_size, $savepath, $cq);
    unset($image);
}

class ImageScaleProcFunc{
    private $sourse;
    private $filename;
    private $cq;
    private $image;
    private $image_info;
    private $limit_size;
    private $ext;
    public function __construct($sourse, $filename, $limit_size, $savepath, $cq)
    {
        $this->sourse     = $sourse;
        $this->filename   = $filename;
        $this->savepath   = $savepath;
        $this->savepath   = $cq;
        $this->image      = new Imagick($this->sourse);
        $this->image_info = $this->image->identifyImage();
        $this->limit_size = $limit_size;
        $this->extOutput();
        $this->compressQy();
        $this->scaleProc();
        $this->saveImage();
    }
    private function extOutput(){
        if($this->image_info["mimetype"] === "image/jpeg"){
            $this->ext = "jpg";
        }else if($this->image_info["mimetype"] === "image/png"){
            $this->ext = "png";
        }else if($this->image_info["mimetype"] === "image/gif"){
            $this->ext = "gif";
        }else if($this->image_info["mimetype"] === "image/bmp"){
            $this->ext = "bmp";
        }
    }
    private function compressQy(){
        $cq = $this->image->getImageCompressionQuality();
        if($cq > $this->cq){
            $this->image->setImageCompressionQuality($this->cq);
        }else{
            $this->image->setImageCompressionQuality($cq);
        }
    }
    private function scaleProc(){
        if($this->image_info["geometry"]["width"] > $this->limit_size && $this->image_info["geometry"]["height"] > $this->limit_size){
            if($this->image_info["geometry"]["width"] > $this->image_info["geometry"]["height"]){
                $image->scaleImage($this->limit_size, 0);
            }else if($this->image_info["geometry"]["height"] > $this->image_info["geometry"]["width"]){
                $this->image->scaleImage(0, $this->limit_size);
            }else if($this->image_info["geometry"]["width"] === $this->image_info["geometry"]["height"]){
                $this->image->scaleImage($this->limit_size, $this->limit_size);
            }
        }else if($this->image_info["geometry"]["width"] > $this->limit_size && $this->image_info["geometry"]["height"] < $this->limit_size){
            $this->image->scaleImage($this->limit_size, 0);
        }else if($this->image_info["geometry"]["height"] > $this->limit_size && $this->image_info["geometry"]["width"] < $this->limit_size){
                $this->image->scaleImage(0, $this->limit_size);
        }
    }
    private function saveImage(){
        $this->image->writeImage($this->filename . "." . $this->ext);
    }
}
