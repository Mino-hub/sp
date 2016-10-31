<?php 

$url = "http://hayabusa.open2ch.net/test/read.cgi/livejupiter/1474364007/";
$thread_hash = sha1($url);

$html    = file_get_contents($url);
$dom     = new DOMDocument();

//$dom
@$dom->loadHTML($html);

//xpathの生成
$xpath   = new DOMXPath($dom);

//contextの設定
//$context = $xpath->query("/html/body/div[@class='thread']/dl[1]")->item(0); DomElement
$context = $xpath->query("/html/body/div[@class='thread']")->item(0);
$dl      = $xpath->query("dl" , $context);

$i=1;
foreach ($dl as $node) {

    var_dump($i."レス目");
    if($i>4){
        break;
    }
    
    $dd = $node->getElementsByTagName("dd")->item(0);
    
    //よけいなares要素の削除
    $ares = $dd->getElementsByTagName("ares")->item(0);
    $dd->removeChild($ares);
    $ddxml = $dom->saveXML($dd);
    var_dump($ddxml);
    
    $div_class_group_tag  = $xpath->query("dl[{$i}]/dd/div[@class='group']" , $context);

    $i++;
}
