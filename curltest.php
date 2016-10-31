<?php
$url = "http://hayabusa.open2ch.net/livejupiter/";
$context = curl_init();
curl_setopt($context, CURLOPT_URL, $url);
curl_setopt($context, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($context);
var_dump($html);
