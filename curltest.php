<?php
// $url = "http://hayabusa.open2ch.net/livejupiter/";
$url = "http://open.open2ch.net/menu/";
$context = curl_init();
curl_setopt($context, CURLOPT_URL, $url);
curl_setopt($context, CURLOPT_HEADER, true);
curl_setopt($context, CURLOPT_HTTPHEADER, ["cookie:"]);
curl_setopt($context, CURLOPT_RETURNTRANSFER, true);
curl_setopt($context, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
curl_setopt($context, CURLOPT_PROXY, '172.20.0.31:9050');
curl_setopt($context, CURLOPT_COOKIEFILE, 'cookie_menu');
curl_setopt($context, CURLOPT_COOKIEJAR, 'cookie_menu');
$html = curl_exec($context);
$info = curl_getinfo($context);
curl_close($context);
var_dump($info);
var_dump($html);
