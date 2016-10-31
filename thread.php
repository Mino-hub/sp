<?php 

$pdo = new PDO("mysql:host=172.20.0.10;dbname=spdb;charset=utf8","root","goma");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$reslut = $pdo->query("SELECT * from menu");
// $pdo->query("TRUNCATE TABLE spdb.thread"); 

//menuデータの取得とデータの整理
$m_i = 0;
foreach ($reslut->fetchall() as $reslut_value) {
    if((int)$reslut_value["get"] === 1){
        $menu[$m_i]["hash"]  = $reslut_value["hash"];
        $menu[$m_i]["url"]   = $reslut_value["url"];
        $menu[$m_i]["title"] = $reslut_value["title"];
    }
    $m_i++;
}

//メインループ
$de = 0;
foreach ($menu as $main_value) {

    if($de > 3){
        break;
    }

    $subdomein   = preg_replace("/\/$/", "", $main_value["url"]);
    var_dump($subdomein);
    $thread_url  = $main_value["url"] . "subback.html";
    $thread_hash = sha1($main_value["url"]);
    $thread_html = file_get_contents($thread_url);

    //domobjの作成取得
    $thread_dom   = new DOMDocument();
    @$thread_dom->loadHTML($thread_html);
    $thread_xpath = new DOMXPath($thread_dom);
    $context      = $thread_xpath->query("/html/body/div/small")->item(0);
    $a_tags       = $thread_xpath->query("a" , $context);

    //aタグのリンクの取得
    $t_i = 0;
    unset($thread);
    foreach ($a_tags as $a_tag) {
        $href  = $a_tag->getAttribute("href");
        $title = $a_tag->nodeValue;
        $regEx = "/[0-9]{1,4}:\s(.+)\s\(([0-9]{1,4})\)/";
        preg_match($regEx, $title, $match);

        $reurl = preg_replace("/l50$/", "", $href);
        preg_match("/[0-9]{10}/", $reurl, $mtime);
        $mtime = $mtime[0];
        var_dump($mtime);
        // var_dump($reurl);

        $thread[$t_i]["hash"]  = sha1($href); 
        $thread[$t_i]["url"]   = $subdomein . $reurl;
        $thread[$t_i]["title"] = $match[1];
        $thread[$t_i]["now"]   = $match[2];
        $thread[$t_i]["mtime"] = $mtime;
        $t_i++;
    }

    //threadデータのインサート
    $ti_i = 0;
    // $stetement = "INSERT IGNORE INTO thread (hash, url, title, now, gtime, mtime) VALUES(:hash, :url, :title, :now, :gtime, :mtime)";
    $stetement = "INSERT IGNORE INTO thread (hash, url, title, now, gtime, mtime) VALUES(:hash, :url, :title, :now, :gtime, :mtime) ON DUPLICATE KEY UPDATE now=:now";
    foreach ($thread as $insert_value) {
        $prepare = $pdo->prepare($stetement);
        $prepare->bindValue(":hash"  , $insert_value["hash"]      , PDO::PARAM_STR);
        $prepare->bindValue(":url"   , $insert_value["url"]       , PDO::PARAM_STR);
        $prepare->bindValue(":title" , $insert_value["title"]     , PDO::PARAM_STR);
        $prepare->bindValue(":now"   , (int)$insert_value["now"]  , PDO::PARAM_INT);
        $prepare->bindValue(":gtime" , time()                     , PDO::PARAM_INT);
        $prepare->bindValue(":mtime" , $insert_value["mtime"]     , PDO::PARAM_INT);
        $prepare->execute();
        $ti_i++;
    }

    $de++;
}


