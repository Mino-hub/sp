<?php
$url = "http://hayabusa.open2ch.net/test/read.cgi/livejupiter/1474364007/";
$thread_hash = sha1($url);

$html    = file_get_contents($url);
$dom     = new DOMDocument();

//$dom; DomDocument
@$dom->loadHTML($html);

//xpathの生成
$xpath   = new DOMXPath($dom);

//contextの設定
//$context = $xpath->query("/html/body/div[@class='thread']/dl[1]")->item(0); DomElement
$context            = $xpath->query("/html/body/div[@class='thread']")->item(0);
$dl = $xpath->query("dl" , $context);
var_dump($dl->length);
