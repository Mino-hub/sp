<?php

function sourse_img_output($image_info, $img_sourse){
    if($image_info["mime"] === "image/jpeg"){
        $image = imagecreatefromjpeg($img_sourse);
    }else if($image_info["mime"] === "image/png"){
        $image = imagecreatefrompng($img_sourse);
    }else if($image_info["mime"] === "image/gif"){
        $image = imagecreatefromgif($img_sourse);
    }else if($image_info["mime"] === "image/bmp"){
        $image = imagecreatefromwbmp($img_sourse);
    };
    return $image;
}

function image_resize_output($w, $h, $image_sourse){
    $rimit = 1000;
    if($w > $h){
        $ratio    = $rimit / $w;
        $resize_w = $rimit;
        $resize_h = round($h * $ratio);
    }else if($h > $w){
        $ratio = $rimit / $h;
        $resize_h = $rimit;
        $resize_w = round($w * $ratio);
    }else if($h === $w){
        if($h > $rimit){
            $resize_h = $rimit;
            $resize_w = $rimit;
        }else{
            $resize_h = $h;
            $resize_w = $w;
        }
    }
    $size["resize_h"] = $resize_h;
    $size["resize_w"] = $resize_w;
    $size["h"] = $h;
    $size["w"] = $w;
    return $size;
}

function resize_image_output(array $size, $sourse_image){
    $dst_image = imagecreatetruecolor($size["resize_w"], $size["resize_h"]);
    $resize_image = $imagecopyresampled($dst_image, $sourse_image, 0, 0, 0, 0, $size["resize_w"], $size["resize_h"], $size["w"], $size["h"]);
    imagedestroy($dst_image);
    return $resize_image;
}

function save_resize_image($image_info, $resize_image, $save_path){
    if($image_info["mime"] === "image/jpeg"){
        imagejpeg($resize_image, $save_path . image_type_to_extension(IMAGETYPE_JPEG, true));
    }else if($image_info["mime"] === "image/png"){
        imagepng($resize_image, $save_path. image_type_to_extension(IMAGETYPE_PNG, true));
    }else if($image_info["mime"] === "image/gif"){
        imagegif($resize_image, $save_path. image_type_to_extension(IMAGETYPE_GIF, true));
    }else if($image_info["mime"] === "image/bmp"){
        imagewbmp($resize_image, $save_path. image_type_to_extension(IMAGETYPE_BMP, true));
    };
}

$img_sourse = "http://i.imgur.com/Kwn8uTt.jpg";
$image_info = getimagesize($img_sourse);
$save_path = "./img";

var_dump($image_info);
