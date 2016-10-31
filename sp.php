<?php
//todo imgタグの取得と画像取り込みのテスト
//todo データのシリアライズ化
//todo アンカーリンクの処理をどうする？->ショートコード化する？
//todo htmlデータの構築テストを並行する必要あり

function img_proc($dom, $dom_elemnt, $p){
    //img要素の取得 imgタグ書き換え用
    //タグ取得がimgではなくaなのは、imgではサムネイルを取得してしまうため
    $all_img = $dom_elemnt->getElementsByTagName("a");
    for($all_img_i = 0 ; $all_img_i < $all_img->length ; $all_img_i++){
        $img_url             = $all_img->item($all_img_i)->getAttribute("href");
        $img_url_hash        = sha1($img_url);
        $img_file_name_regEx = "/[_\-\.a-zA-Z0-9]+(\.[jpegnifbm]{3,4})$/"; 

        preg_match($img_file_name_regEx, $img_url, $img_name_match);

        $all_img_array[$all_img_i]["img_url"]          = $img_url;
        $all_img_array[$all_img_i]["img_ext"]          = $img_name_match[1];
        $all_img_array[$all_img_i]["img_file_name"]    = $img_name_match[0];
        $all_img_array[$all_img_i]["img_rename"]       = $img_url_hash . $img_name_match[1];
        $all_img_array[$all_img_i]["img_replace_hash"] = $img_url_hash;
        $all_img_array[$all_img_i]["img_tag"]          = $dom->saveXML($all_img->item($all_img_i));
    }
    // var_dump($all_img_array);
    return $all_img_array;
}
function img_proc_a($dom, $dom_elemnt){
    //img要素の取得 imgタグ書き換え用
    //タグ取得がimgではなくaなのは、imgではサムネイルを取得してしまうため
    $img_a                 = $dom_elemnt;
    $img_a_url             = $img_a->getAttribute("href");
    $img_a_url_hash        = sha1($img_a_url);
    $img_a_file_name_regEx = "/[_\-\.a-zA-Z0-9]+(\.[jpegnifbm]{3,4})$/"; 

    preg_match($img_a_file_name_regEx, $img_a_url, $img_a_name_match);

    $img_a_tag[0]["img_url"]          = $img_a_url;
    $img_a_tag[0]["img_ext"]          = $img_a_name_match[1];
    $img_a_tag[0]["img_file_name"]    = $img_a_name_match[0];
    $img_a_tag[0]["img_rename"]       = $img_a_url_hash . $img_a_name_match[1];
    $img_a_tag[0]["img_replace_hash"] = $img_a_url_hash;
    $img_a_tag[0]["img_tag"]          = $dom->saveXML($img_a);
    return $img_a_tag;
}

function regEx_replacer($regEx_source, $replacer, $replacement, $for_quote, $quote){
    $replace_regEx = preg_quote($regEx_source);
    $replace_regEx = preg_replace("/\//", "\/", $replace_regEx); 
    $replace_regEx = "/" . $replace_regEx . "/";
    $replaced = preg_replace($replace_regEx, $replacer, $replacement); 
    return $replaced;
}

// $url = "http://hayabusa.open2ch.net/test/read.cgi/livejupiter/1475650256";
// $url = "http://open.open2ch.net/test/read.cgi/oekaki/1470136915/";
// $url = "http://hayabusa.open2ch.net/test/read.cgi/livejupiter/1477241379/";
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
$dl_tag             = $xpath->query("dl" , $context);

$res[] = [];
$i = 1;
foreach ($dl_tag as $node) {
    var_dump($i."レス目");
    if($i>4){
        break;
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // 必要な素材をいろいろ取得する
    //
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // dt要素の特定
    $dt = $node->getElementsByTagName("dt")->item(0);

    //レス番号　属性から取得 <dt res=1>
    $res_no = $dt->getAttribute("res");
    $res[$i]["res_no"] = $res_no;

    //ヘッダ（名前・時間・id)の取得 dtのNodeValue
    $res_header = $dt->nodeValue;
    $res_header_match_regEx = "/[0-9]+\s\xEF\xBC\x9A(\S+)\s?\xEF\xBC\x9A(20[0-9]{2}\/[0-9]{2}\/[0-9]{2}.+[0-9]{2}:[0-9]{2}:[0-9]{2})/";
    preg_match_all($res_header_match_regEx, $res_header, $match, PREG_SET_ORDER);
    $res[$i]["res_user_name"] = $match[0][1];
    $res[$i]["res_date"]      = $match[0][2];

    //ddレス要素の特定
    $dd = $node->getElementsByTagName("dd")->item(0);

    //レス番号　属性から取得 <dt res=1>
    $res_no = $dt->getAttribute("res");
    $res[$i]["res_no"] = $res_no;

    //ヘッダ（名前・時間・id)の取得 dtのNodeValue
    $res_header = $dt->nodeValue;
    $res_header_match_regEx = "/[0-9]+\s\xEF\xBC\x9A(\S+)\s?\xEF\xBC\x9A(20[0-9]{2}\/[0-9]{2}\/[0-9]{2}.+[0-9]{2}:[0-9]{2}:[0-9]{2})/";
    preg_match_all($res_header_match_regEx, $res_header, $match, PREG_SET_ORDER);
    $res[$i]["res_user_name"] = $match[0][1];
    $res[$i]["res_date"]      = $match[0][2];

    //ddレス要素の特定
    $dd = $node->getElementsByTagName("dd")->item(0);

    //ユーザーID の取得
    $user_id = $dd->getAttribute("class");
    $user_id_replace_regEx1 = "/^id/";// "idAAA" or "idAAA mesg"
    $user_id_replace_regEx2 = "/\smesg/";
    $user_id = preg_replace($user_id_replace_regEx1, "", $user_id);
    $user_id = preg_replace($user_id_replace_regEx2, "", $user_id);
    $res[$i]["res_user_id"] = $user_id;

    //よけいなares要素の特定
    $ares = $dd->getElementsByTagName("ares")->item(0);

    //ddからよけいなares要素を削除
    $dd->removeChild($ares);

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // 以下の処理は<ares>タグに含まれているaタグを回避する為にremoveChildでaresタグを削除してから行う
    // なので処理の順番を変えてはいけない
    //
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //img差し替えデータベース用のimg一覧データ
    $dd_a = $dd->getElementsByTagName("a");
    if($dd_a->length !== 0){
        foreach ($dd_a as $value) {
            $is_img = $value->getElementsByTagName("img")->length;
            if($is_img !== 0){
                $res[$i]["database_img_array"] = img_proc($dom, $dd, "差し替え用");
                $res[$i]["data_base_is_img"]   = true;
            }else{
                $res[$i]["database_img_array"] = "";
                $res[$i]["data_base_is_img"]   = false;
            }
        }
    }else{
        $res[$i]["database_img_array"] = "";
        $res[$i]["data_base_is_img"]   = false;
    }
   
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // divタグのしょり imgを囲っているdivタグの書き換え準備が主
    //
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //imgタグを束ねてるdivタグ取得(class='group')
    $div_class_group_tag  = $xpath->query("dl[{$i}]/dd/div[@class='group']" , $context);

    //divのフラグ
    if($div_class_group_tag->length !== 0){
        $res[$i]["is_div_img_group"] = true;
        for($div_i = 0 ; $div_i < $div_class_group_tag->length ; $div_i++){
            $res_dd_div_tag_array[$div_i]["div_array"] = $dom->saveXML($div_class_group_tag->item($div_i));
            $res_dd_div_tag_array[$div_i]["img_array"] = img_proc($dom, $div_class_group_tag->item($div_i), "divフラグ");
        }
    }else{
        $res[$i]["is_div_img_group"] = false;
        $res_dd_div_tag_array[]      = "";
    }

    $res[$i]["div_img_group"] = $res_dd_div_tag_array;
    //使った作業用配列を初期化する しないと際限なく大きくなるよ
    unset($res_dd_div_tag_array);
    unset($img_array);

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // aタグのしょり アンカーの書き換え準備が主 
    //
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //ddタグ内のaタグ取得
    $a = $dd->getElementsByTagName("a");

    //ddタグ内のaタグの処理
    //aタグがあるかどうか？
    $res[$i]["is_anker"] = false;
    if($a->length !== 0){//<--a
        for($a_i = 0 ; $a_i < $a->length ; $a_i++){
            //aタグのhrefを取得
            $a_href = $a->item($a_i)->getAttribute("href");

            //aタグがアンカーだった場合にアンカー先番号を取得する
            $a_href_anker_no_match_regEx = "/\/([0-9]{1,4})$/";

            //戻り値はアンカーかどうかの判断に使う 取得できたらアンカーであるという判断
            $is_anker = preg_match($a_href_anker_no_match_regEx, $a_href, $a_href_anker_no);
            $img = $a->item($a_i)->getElementsByTagName("img");

            //アンカのフラグ
            if($is_anker === 1){//<--anker
                $res[$i]["is_anker"]                             = true;
                $res_dd_a_tag_array[$a_i]["is_anker"]            = true; 
                $res_dd_a_tag_array[$a_i]["is_img"]              = false; 
                $res_dd_a_tag_array[$a_i]["a_tag_href"]          = $a_href;
                $res_dd_a_tag_array[$a_i]["a_tag_href_anker_no"] = $a_href_anker_no[1];
                $res_dd_a_tag_array[$a_i]["a_tag"]               = $dom->saveXML($a->item($a_i));
            }else if($img->length !== 0 && $is_anker === 0){//<--img
                $res[$i]["is_anker"]                   = false;
                $res_dd_a_tag_array[$a_i]["is_anker"]  = false; 
                $img_array = img_proc_a($dom, $a->item($a_i), "安価");
                $res_dd_a_tag_array[$a_i]["is_img"]    = true; 
                $res_dd_a_tag_array[$a_i]["img_array"] = $img_array;
                $res_dd_a_tag_array[$a_i]["a_tag"]     = $dom->saveXML($a->item($a_i));
            }else if($img->length === 0 && $is_anker === 0){
                $res[$i]["is_anker"]                   = false;
                $res_dd_a_tag_array[$a_i]["is_anker"]  = false; 
                $res_dd_a_tag_array[$a_i]["is_img"]    = false; 
                $res_dd_a_tag_array[$a_i]["img_array"] = "";
                $res_dd_a_tag_array[$a_i]["a_tag"]     = $dom->saveXML($a->item($a_i));
            }else{
                $res[$i]["is_anker"]                   = false;
                $res_dd_a_tag_array[$a_i]["is_anker"]  = false; 
                $res_dd_a_tag_array[$a_i]["is_img"]    = false; 
                $res_dd_a_tag_array[$a_i]["img_array"] = [];
                $res_dd_a_tag_array[$a_i]["a_tag"]     = "";
            }
        }
        //ddタグの中にaタグが存在するフラグ
        $res[$i]["is_a_tag"] = true;
    }else{//a
        $res_dd_a_tag_array[] = "";

        //ddタグの中にaタグが存在しないフラグ
        $res[$i]["is_a_tag"] = false;
    }//a-->

    $res[$i]["a_tag_array"] = $res_dd_a_tag_array;
    //使った作業用配列を初期化する しないと際限なく大きくなるよ
    unset($res_dd_a_tag_array);

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // ddタグのしょり レスの取得及びレスの加工が主 
    //
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //レスの取得（brを含んだ状態の<dd>タグごと）
    $res_text = $dom->saveXML($dd);
    //ddタグを削除する
    $res_text_replace_regEx1 = "/^<dd class=\"id.{3}\s?[mseg]*\">/";
    $res_text_replace_regEx2 = "/<\/dd>/";
    $res_text = preg_replace($res_text_replace_regEx1, "", $res_text);
    $res_text = preg_replace($res_text_replace_regEx2, "", $res_text);
    //未加工の状態も一応残しておく
    $row_res_text = $res_text;

    //div groupの書き換え
    //*****************************************************************************************
    //* aタグimgよりも先に処理しないと書き換えがうまくいかないのでaタグ処理との順番の変更はだめ
    //*****************************************************************************************
    //
    if($res[$i]["is_div_img_group"] === true){
        foreach ($res[$i]["div_img_group"] as $item) {
            foreach ($item["img_array"] as $value) {
                $div_replacer .= "[img_replace img_hash=\"" . $value["img_replace_hash"] . "\"]";
            }
            $res_text = regEx_replacer($item["div_array"], $div_replacer,  $res_text, "/\//", "\/");
            $res_text = regEx_replacer("<br clear=\"both\"/>", "", $res_text, "/\//", "\/");
            unset($div_replacer);
        }
    }

    //アンカーリンクの書き換え href='#100'形式
    //html作成時にレスブロックにレスナンバーのidが必要になる
    if($res[$i]["is_a_tag"] === true){
        foreach ($res[$i]["a_tag_array"] as $item) {
            if($item["is_anker"] === true){
                $anker_replacer = "#" . $item["a_tag_href_anker_no"]; 
                $res_text       = regEx_replacer($item["a_tag_href"], $anker_replacer,  $res_text, "/\//", "\/");
            }else if($item["is_img"] === true){
                foreach ($item["img_array"] as $value) {
                    $a_replacer .= "[img_replace img_hash=\"" . $value["img_replace_hash"] . "\"]";
                }
                $res_text = regEx_replacer($item["a_tag"], $a_replacer,  $res_text, "/\//", "\/");
                unset($anker_replacer);
                // $res_text = regEx_replacer("<br clear=\"both\"/>", "", $res_text, "/\//", "\/");
            }else if($item["is_anker"] === false && $item["is_img"] === false){

            }
        }
    }

    $res[$i]["row_res_text"] = $row_res_text;
    $res[$i]["res_text"]     = $res_text;

    //レスのハッシュ
    $hash_text = 
        $url . 
        $res[$i]["res_no"] . 
        $res[$i]["res_date"] . 
        $res[$i]["res_user_name"] . 
        $res[$i]["res_user_id"] . 
        $res[$i]["row_res_text"];

    $res_hash = sha1($hash_text);
    $res[$i]["res_hash"]     = $res_hash;


    //データベースにインサートする用のデータ
    $database_array[$i]["thread_hash"]   = $thread_hash;
    $database_array[$i]["res_hash"]      = $res[$i]["res_hash"];
    $database_array[$i]["res_no"]        = $res[$i]["res_no"];
    $database_array[$i]["res_user_name"] = $res[$i]["res_user_name"];
    $database_array[$i]["res_date"]      = $res[$i]["res_date"];
    $database_array[$i]["res_user_id"]   = $res[$i]["res_user_id"];
    $database_array[$i]["res_text"]      = $res[$i]["res_text"];
    $database_array[$i]["is_img"]        = $res[$i]["data_base_is_img"];
    $database_array[$i]["img_array"]     = $res[$i]["database_img_array"];
    $database_array[$i]["is_anker"]      = $res[$i]["is_anker"];
    $database_array[$i]["anker_array"]   = $res[$i]["a_tag_array"];

    $i++;
    $k++;
}
    var_dump($database_array);
