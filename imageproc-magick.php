<?php
$sourse[0]["sourse"]   = "http://i.imgur.com/4L2rYZv.jpg";
$sourse[0]["filename"] = "0009900decca20c822900acdeffea730";
$sourse[1]["sourse"]   = "http://i.imgur.com/9KySaHr.jpg";
$sourse[1]["filename"] = "111dbc9dddda20c822900acdeffea730";
// $savepath = "";
var_dump($sourse);
$limit_size = 800;

foreach ($sourse as $value) {
    $image = new Imagick($value["sourse"]);
    scale_to_write($image, $value["filename"], $limit_size);
}

function scale_to_write($image, $savepath, $limit_size){
    $image_info = $image->identifyImage();
    // var_dump($image_info);
    if      ($image_info["mimetype"] === "image/jpeg"){
        $ext = "jpg";
        $cq = $image->getImageCompressionQuality();
        if($cq > 40){
            $image->setImageCompressionQuality(40);
        }else{
            $image->setImageCompressionQuality($cq);
        }

    }else if($image_info["mimetype"] === "image/png"){
        $ext = "png";
    }else if($image_info["mimetype"] === "image/gif"){
        $ext = "gif";
    }else if($image_info["mimetype"] === "image/bmp"){
        $ext = "bmp";
    }

    if($image_info["geometry"]["width"] > $limit_size && $image_info["geometry"]["height"] > $limit_size){
        if($image_info["geometry"]["width"] > $image_info["geometry"]["height"]){
            $image->scaleImage($limit_size, 0);
        }else if($image_info["geometry"]["height"] > $image_info["geometry"]["width"]){
            $image->scaleImage(0, $limit_size);
        }else if($image_info["geometry"]["width"] === $image_info["geometry"]["height"]){
            $image->scaleImage($limit_size, $limit_size);
        }
    }else if($image_info["geometry"]["width"] > $limit_size && $image_info["geometry"]["height"] < $limit_size){
        $image->scaleImage($limit_size, 0);
    }else if($image_info["geometry"]["height"] > $limit_size && $image_info["geometry"]["width"] < $limit_size){
            $image->scaleImage(0, $limit_size);
    }
$image->writeImage($savepath . "." . $ext);
}
