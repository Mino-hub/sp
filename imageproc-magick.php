<?php
// $s = "http://i.imgur.com/4L2rYZv.jpg";
$s = "http://i.imgur.com/Wnq1G38.gif";

// $s = "http://i.imgur.com/Wnq1G38s.gif";
// $s = "http://i.imgur.com/HC3ds4t.gif";
$sourse[0]["sourse"]   = "http://i.imgur.com/4L2rYZv.jpg";
$sourse[0]["filename"] = "0009900decca20c822900acdeffea730";
$sourse[1]["sourse"]   = "http://i.imgur.com/9KySaHr.jpg";
$sourse[1]["filename"] = "111dbc9dddda20c822900acdeffea730";
$savepath = "";
$limit_size = 800;
$cq = 40;

// foreach ($sourse as $value) {
//     $image = new ImageScaleProc($value["sourse"], $value["filename"], $limit_size, $savepath, $cq);
//     unset($image);
// }
class ImgaeScaleProc{
    public function __construct(){
    }
}

class Compress{
    public function compressQySet(Iimage $image){
        $cq = $image->getImageCompressionQuality();
        if($cq > $image->cq){
            $image->setImageCompressionQuality($image->cq);
        }else{
            $image->image->setImageCompressionQuality($cq);
        }
    }
}
class Scale{
    public function scaleProc(Iimage $image){
        if($image->image_info["geometry"]["width"] > $image->limit_size && $image->image_info["geometry"]["height"] > $image->limit_size){
            if($image->image_info["geometry"]["width"] > $image->image_info["geometry"]["height"]){
                $image->scaleImage($image->limit_size, 0);
            }else if($image->image_info["geometry"]["height"] > $image->image_info["geometry"]["width"]){
                $image->image->scaleImage(0, $image->limit_size);
            }else if($image->image_info["geometry"]["width"] === $image->image_info["geometry"]["height"]){
                $image->image->scaleImage($image->limit_size, $image->limit_size);
            }
        }else if($image->image_info["geometry"]["width"] > $image->limit_size && $image->image_info["geometry"]["height"] < $image->limit_size){
            $image->image->scaleImage($image->limit_size, 0);
        }else if($image->image_info["geometry"]["height"] > $image->limit_size && $image->image_info["geometry"]["width"] < $image->limit_size){
                $image->image->scaleImage(0, $image->limit_size);
        }
    }
}
class Save{
    public function saveImage(Iimage $image, $filename, $ext){
        $image->image->writeImage($image->filename . "." . $image->ext);
    }
}
class Image extends Imagick{
    private $sourse;
    private $filename;
    private $cq;
    private $image_info;
    private $limit_size;
    private $ext;
    private $is_gif;
    public function __construct($sourse, $filename, $limit_size, $savepath, $cq)
    {
        parent::__construct($sourse);
        $this->sourse     = $sourse;
        $this->filename   = $filename;
        $this->savepath   = $savepath;
        $this->cq         = $cq;
        $this->image_info = $this->identifyImage();
        $this->limit_size = $limit_size;
        $this->extSet();
        $this->IsGifAnime();
    }
    private function mimetypeOutput(){
        return $this->image_info["mimetype"];
    }
    private function extSet(){
        if($this->mimetypeOutput() === "image/jpeg"){
            $this->ext = ".jpg";
        }else if($this->mimetypeOutput() === "image/png"){
            $this->ext = ".png";
        }else if($this->mimetypeOutput() === "image/gif"){
            $this->ext = ".gif";
        }else if($this->mimetypeOutput() === "image/bmp"){
            $this->ext = ".bmp";
        }
    }
    private function IsGifAnime(){
        var_dump($this->getNumberImages());
        if($this->getNumberImages() > 1){
            $flag = true;
        }else{
            $flag = false;
        };
        $this->is_gif = $flag;
    }
    public function __get($propaty){
        return $this->$propaty;
    }
}
$image = new Image($s, "222222", $limit_size, $savepath, $cq);
// var_dump($image);

