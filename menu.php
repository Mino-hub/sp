<?php 

$menu_url  = "http://open2ch.net/menu/pc_menu.html";

$menu_html = file_get_contents($menu_url);
$menu_dom  = new DOMDocument();

//$dom; DomDocument
@$menu_dom->loadHTML($menu_html);

//xpathの生成
$menu_xpath   = new DOMXPath($menu_dom);

//contextの設定
$context = $menu_xpath->query("/html/body/font")->item(0);
$a  = $menu_xpath->query("a" , $context);

$i = 0;
unset($menu);
foreach ($a as $item) {
    $href  = $item->getAttribute("href");
    if($href === "mailto:open2ch@satoru.net" || $href === "http://engawa.open2ch.net/accuse/"){
        continue;
    }
    $title = $item->nodeValue;
    $menu[$i]["hash"]  = sha1($href); 
    $menu[$i]["url"]   = $href;
    $menu[$i]["title"] = $title;
    $i++;
}
var_dump($menu);

$pdo = new PDO("mysql:host=172.17.0.4;dbname=spdb;charset=utf8","root","goma");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$i = 0;
foreach ($menu as $value) {
    $pdo->query("INSERT INTO menu (hash, url, title) VALUES('{$menu[$i]["hash"]}', '{$menu[$i]["url"]}', '{$menu[$i]["title"]}')"); 
    $i++;
}
