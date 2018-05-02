<?php

//post受け取り
$id = filter_input( INPUT_POST, 'id');
$name = filter_input( INPUT_POST, 'name');
$album = filter_input( INPUT_POST, 'album');
$url = filter_input( INPUT_POST, 'url');
$comment = filter_input( INPUT_POST, 'comment');
$tracklist = filter_input( INPUT_POST, 'tracklist');

$names =  preg_replace("/( |　)/", "", $name);
$albums =  preg_replace("/( |　)/", "", $album);

$serverurl = $_SERVER["HTTP_HOST"];
if($serverurl == 'localhost'){
  $dsn = 'mysql:dbname=gs_db;charset=utf8;host=localhost';
  $username = 'root';
  $password = '';
}else{
    $dsn = 'mysql:dbname=yamashita-ksk_gs_db;charset=utf8;host=mysql425.db.sakura.ne.jp';    
    $username = 'yamashita-ksk';
    $password = 'kou-08201893';
}

//DB接続
try{
    $pdo = new PDO($dsn,$username,$password);
}catch (PDOException $e) {
    exit('DBConnectError:'.$e->getMessage());
  }


//PHPquery
require_once("phpQuery-onefile.php");
$html = file_get_contents("https://www.google.co.jp/search?biw=1318&bih=755&tbm=isch&sa=1&ei=uSgfWubwFoWv0gTjwJvoCg&q=".$names .'+'.$albums);
$html_3 = file_get_contents('https://www.youtube.com/results?search_query='.$names.'+'.$albums);
$html_4 = file_get_contents('https://www.youtube.com/results?search_query='.$names.'+'.$albums);
$dom = phpQuery::newDocument($html);
$dom_3 = phpQuery::newDocument($html_3);
$dom_4 = phpQuery::newDocument($html_4);
$imageurl =  $dom["body"]->find('img')->attr("src");
$videoimage =  $dom_3[".yt-thumb-simple:eq(0)"]->find('img')->attr("src");
$videourls =  $dom_4[".yt-lockup-title:eq(0)"]->find('a')->attr("href");
$videourl = 'https://www.youtube.com'.$videourls;

//update.bind
$stmt = $pdo->prepare("UPDATE gs_bm2_table SET name=:name ,album=:album, url=:url, tracklist=:tracklist,comment=:comment,imageurl=:imageurl,videourl=:videourl,videoimage=:videoimage WHERE id=:id");
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':album', $album, PDO::PARAM_STR);
$stmt->bindValue(':url', $url, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':imageurl', $imageurl, PDO::PARAM_STR);
$stmt->bindValue(':videourl', $videourl, PDO::PARAM_STR);
$stmt->bindValue(':videoimage', $videoimage, PDO::PARAM_STR);
$stmt->bindValue(':tracklist', $tracklist, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

//データ登録後
  if($status==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("QueryError:".$error[2]);
  }else{
    //５．index.phpへリダイレクト
    header("Location: discmark_list.php");
    exit;
  }



?>