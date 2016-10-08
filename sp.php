<?php
//todo aタグの種別選択どうする？
//todo imgタグの取得と画像取り込みのテスト
//todo データのシリアライズ化
//todo アンカーリンクの処理をどうする？
//todo divに囲まれていないa->imgの処理
//todo img処理の関数化 aタグimg　div囲みimgごとに処理する必要がありそう

// $url = "http://hayabusa.open2ch.net/test/read.cgi/livejupiter/1475650256";
$url = "http://open.open2ch.net/test/read.cgi/oekaki/1470136915/";
$url_hash = sha1($url);

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
    // if($i>4){
    //     break;
    // }

    
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
    $res[$i]["res_name"] = $match[0][1];
    $res[$i]["res_date"] = $match[0][2];

    //ddレス要素の特定
    $dd = $node->getElementsByTagName("dd")->item(0);


    //ユーザーID の取得
    $user_id = $dd->getAttribute("class");
    $user_id_replace_regEx1 = "/^id/";// "idAAA" or "idAAA mesg"
    $user_id_replace_regEx2 = "/\smesg/";
    $user_id = preg_replace($user_id_replace_regEx1, "", $user_id);

    //レス番号　属性から取得 <dt res=1>
    $res_no = $dt->getAttribute("res");
    $res[$i]["res_no"] = $res_no;

    //ヘッダ（名前・時間・id)の取得 dtのNodeValue
    $res_header = $dt->nodeValue;
    $res_header_match_regEx = "/[0-9]+\s\xEF\xBC\x9A(\S+)\s?\xEF\xBC\x9A(20[0-9]{2}\/[0-9]{2}\/[0-9]{2}.+[0-9]{2}:[0-9]{2}:[0-9]{2})/";
    preg_match_all($res_header_match_regEx, $res_header, $match, PREG_SET_ORDER);
    $res[$i]["res_name"] = $match[0][1];
    $res[$i]["res_date"] = $match[0][2];

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
    
    //img要素の取得 imgタグ書き換え用
    $all_img = $dd->getElementsByTagName("img");

    for($all_img_i = 0 ; $all_img_i < $all_img->length ; $all_img_i++){

        $img_url             = $all_img->item($all_img_i)->getAttribute("data-original");
        $img_url_hash        = sha1($img_url);
        $img_file_name_regEx = "/[_\-\.a-zA-Z0-9]+(\.[jpegnifbm]{3,4})$/"; 
        preg_match($img_file_name_regEx, $img_url, $img_name_match);

        $all_img_array[$all_img_i]["img_url"]          = $img_url;
        $all_img_array[$all_img_i]["img_ext"]          = $img_name_match[1];
        $all_img_array[$all_img_i]["img_file_name"]    = $img_name_match[0];
        $all_img_array[$all_img_i]["img_replace_name"] = $img_url_hash;
    }

    $res[$i]["all_img"] = $all_img_array;
    unset($all_img_array);

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // divタグのしょり imgを囲っているdivタグの書き換え準備が主
    //
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //imgタグを束ねてるdivタグ取得(class='group')
    $div_class_group_tag  = $xpath->query("dl[{$i}]/dd/div[@class='group']" , $context);

    //divのフラグ
    if($div_class_group_tag->length !== 0){

        $res[$i]["is_div_group"] = true;
        for($div_i = 0 ; $div_i < $div_class_group_tag->length ; $div_i++){

            $img = $div_class_group_tag->item($div_i)->getElementsByTagName("img"); 

            for($img_i = 0 ; $img_i < $img->length ; $img_i++) {

                $img_array[] = $img->item($img_i)->getAttribute("data-original");

            }

            $res_dd_div_tag_array[$div_i]["img_array"] = $img_array;
            $res_dd_div_tag_array[$div_i]["div_tag"] = $dom->saveXML($div_class_group_tag->item($div_i));
            unset($img_array);
        }

    }else{

        $res[$i]["is_div_group"] = false;
        $res_dd_div_tag_array[] = "";

    }

    // $res[$i]["res_dd_div_tag_array"] = $res_dd_div_tag_array;
    unset($res_dd_div_tag_array);

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // aタグのしょり アンカーの書き換え準備が主 
    //
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //ddタグ内のaタグ取得
    $a = $dd->getElementsByTagName("a");

    //ddタグ内のaタグの処理
    //aタグがあるかどうか？
    if($a->length !== 0){//<--a
        for($a_i = 0 ; $a_i < $a->length ; $a_i++){

            //aタグのhrefを取得
            $a_href = $a->item($a_i)->getAttribute("href");

            //aタグがアンカーだった場合にアンカー先番号を取得する
            $a_href_anker_no_match_regEx = "/\/([0-9]{1,4})$/";

            //戻り値はアンカーかどうかの判断に使う 取得できたらアンカーであるという判断
            $is_anker = preg_match($a_href_anker_no_match_regEx, $a_href, $a_href_anker_no);

            //アンカのフラグ
            if($is_anker === 1){//<--anker

                $res_dd_a_tag_array[$a_i]["is_anker"]            = true;
                $res_dd_a_tag_array[$a_i]["is_img"]              = false; 
                $res_dd_a_tag_array[$a_i]["a_tag_href"]          = $a_href;
                $res_dd_a_tag_array[$a_i]["a_tag_href_anker_no"] = $a_href_anker_no[1];
                $res_dd_a_tag_array[$a_i]["a_tag"]               = $dom->saveXML($a->item($a_i));

            }else{//anker

                //imgタグをもっているかどうか?
                $img = $a->item($a_i)->getElementsByTagName("img");

                if($img->length !== 0){//<--img

                    for($img_i = 0 ; $img_i < $img->length ; $img_i++){

                        $img_data_original[] = $img->item($img_i)->getAttribute("data-original");

                    }

                    $res_dd_a_tag_array[$a_i]["is_img"]                  = true; 
                    $res_dd_a_tag_array[$a_i]["a_tag_img_data_original"] = $img_data_original;
                    unset($img_data_original);

                }else{//img

                    $res_dd_a_tag_array[$a_i]["is_img"]                  = false; 
                    $res_dd_a_tag_array[$a_i]["a_tag_img_data_original"] = "";

                }
                //img-->
                
                //アンカーなかったっす
                $res_dd_a_tag_array[$a_i]["is_anker"] = false;
            }//anker-->
        }
        //ddタグの中にaタグが存在するフラグ
        $res[$i]["is_a_tag"] = true;

    }else{//a

        $res_dd_a_tag_array[] = "";

        //ddタグの中にaタグが存在しないフラグ
        $res[$i]["is_a_tag"] = false;
    }//a-->

    // $res[$i]["res_dd_a_tag_array"] = $res_dd_a_tag_array;

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

    //アンカーリンクの書き換え href='#100'形式
    //レスブロックにレスナンバーのidが必要になる
    if($res[$i]["is_a_tag"] === true){
        foreach ($res_dd_a_tag_array as $item) {
            if($item["is_anker"] === true){
                $a_tag_href_anker_replacer = "#" . $item["a_tag_href_anker_no"]; 
                $a_tag_href_anker_replace_regEx = preg_quote($item["a_tag_href"]);
                $a_tag_href_anker_replace_regEx = preg_replace("/\//", "\/", $a_tag_href_anker_replace_regEx); 
                $a_tag_href_anker_replace_regEx = "/" . $a_tag_href_anker_replace_regEx . "/";
                $res_text = preg_replace($a_tag_href_anker_replace_regEx,$a_tag_href_anker_replacer, $res_text); 
            }
        }
    }

    //todo div=class groupno

    $hash_text = 
        $url_hash . 
        $res[$i]["res_no"] . 
        $res[$i]["res_data"] . 
        $res[$i]["res_user_name"] . 
        $res[$i]["res_user_id"] . 
        $res[$i]["row_res_text"];

    $res_hash = sha1($hash_text);
    $res[$i]["res_hash"]     = $res_hash;

    // $res[$i]["row_res_text"] = $row_res_text;
    $res[$i]["res_text"]     = $res_text;


    //使った作業用配列を初期化する しないと際限なく大きくなるよ
    unset($res_dd_a_tag_array);

    $i++;
    $k++;
}
    var_dump($res);
