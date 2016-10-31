<?php
$url = "http://hayabusa.open2ch.net/livejupiter/";
$context = curl_init();
curl_setopt($context, CURLOPT_URL, $url);
curl_setopt($context, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_PROXYPORT, 9050);//アクセスポート
curl_setopt($ch, CURLOPT_PROXY, "localhost");//IPアドレスかURL
$html = curl_exec($context);
var_dump($html);
