<?php
$url = "http://hayabusa.open2ch.net/livejupiter/";
$context = curl_init();
curl_setopt($context, CURLOPT_URL, $url);
curl_setopt($context, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($context, CURLOPT_PROXYPORT, 9050);//アクセスポート
// curl_setopt($context, CURLOPT_PROXY, "127.0.0.1");//IPアドレスかURL
$html = curl_exec($context);
$info = curl_getinfo($context);
curl_close($context);
var_dump($info);
// var_dump($html);
