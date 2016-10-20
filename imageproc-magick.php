<?php
$sourse = "http://i.imgur.com/9KySaHr.jpg";
$savepath = "image.jpg";
$image = new Imagick($sourse);
$image->writeImage($savepath);
