<?php
$url = "http://hayabusa.open2ch.net/livejupiter/";
$context = curl_init();
curl_setopt($context, CURLOPT_URL, $url);
curl_setopt($context, CURLOPT_RETURNTRANSFER, true);
curl_setopt($context, CURLOPT_COOKIEFILE, 'cookie');
curl_setopt($context, CURLOPT_COOKIE, '');
curl_setopt($context, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
curl_setopt($context, CURLOPT_PROXY, '172.20.0.31:9050');
$html = curl_exec($context);
$info = curl_getinfo($context);
curl_close($context);
var_dump($info);
var_dump($html);
