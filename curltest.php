<?php
// $url = "http://hayabusa.open2ch.net/livejupiter/";
$url = "http://open.open2ch.net/menu/";
$headers = array(
    "HTTP/1.0",
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
    "Accept-Encoding:gzip ,deflate",
    "Accept-Language:ja,en-us;q=0.7,en;q=0.3",
    "Connection:keep-alive",
    "cookie:",
    "User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:26.0) Gecko/20100101 Firefox/26.0"
);
$context = curl_init();
curl_setopt($context, CURLOPT_URL, $url);
// curl_setopt($context, CURLOPT_HEADER, true);
curl_setopt($context, CURLOPT_HTTPHEADER, $headers);
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
