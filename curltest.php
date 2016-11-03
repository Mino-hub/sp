<?php
$url = "http://hayabusa.open2ch.net/livejupiter/";
// $url = "http://104.20.36.84/livejupiter/";
// $url = "http://qiita.com/snize/items/fad7f4451d19903f8ac9";
// $context = curl_init();
// curl_setopt($context, CURLOPT_URL, $url);
// curl_setopt($context, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($context, CURLINFO_HEADER_OUT, true);
// // curl_setopt($context, CURLOPT_AUTOREFERER, true);
//
// // curl_setopt($context, CURLOPT_HEADER, true);
// // curl_setopt($context, CURLOPT_HTTPHEADER, ["User-Agent: curl/7.38.0"]);
//
// // curl_setopt($context, CURLOPT_HTTPPROXYTUNNEL, true);
// curl_setopt($context, CURLOPT_PROXY, '172.20.0.31:8123');
// curl_setopt($context, CURLOPT_COOKIEJAR, 'c');
//
// $html = curl_exec($context);
// $info = curl_getinfo($context);
// curl_close($context);
// var_dump($info);

// $context = curl_init();
// curl_setopt($context, CURLOPT_URL, "172.20.0.31:9050");
// $html = curl_exec($context);
// $info = curl_getinfo($context);
// curl_close($context);
// var_dump($info);

// $exec = shell_exec("curl -s -c -x 172.20.0.31:8123 ipinfo.io");
$exec = exec("curl -s -c -x 172.20.0.31:8123 ipinfo.io", $output);
// var_dump($exec);
var_dump($output);
