<?php
session_start();

$user_num = $_SESSION['id'];
$user_name = $_SESSION['name'];

//post取得
$name = filter_input( INPUT_POST, 'name');
$album = filter_input( INPUT_POST, 'album');
$comment = filter_input( INPUT_POST, 'comment');

$names =  preg_replace("/( |　)/", "", $name);
$albums =  preg_replace("/( |　)/", "", $album);

$names_2 = str_replace(' ','+',$name);
$albums_2 = str_replace(' ','+',$album);

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
$html_2 = file_get_contents("https://www.google.co.jp/search?q=".$names_2);
$html_3 = file_get_contents('https://www.youtube.com/results?search_query='.$names_2.'+'.$albums_2);
$html_4 = file_get_contents('https://www.youtube.com/results?search_query='.$names_2.'+'.$albums_2);
$html_5 = file_get_contents('http://www.hmv.co.jp/search/music/adv_1/category_1/keyword_'.$names_2.'+'.$albums_2.'/target_MUSIC/type_sr/');
$dom = phpQuery::newDocument($html);
$dom_2 = phpQuery::newDocument($html_2);
$dom_3 = phpQuery::newDocument($html_3);
$dom_4 = phpQuery::newDocument($html_4);
$dom_5 = phpQuery::newDocument($html_5);
$imageurl =  $dom["body"]->find('img')->attr("src");
$urls =  $dom_2["cite:eq(0)"]->text();
$videoimage =  $dom_3[".yt-thumb-simple:eq(0)"]->find('img')->attr("src");
$videourls =  $dom_4[".yt-lockup-title:eq(0)"]->find('a')->attr("href");
$videourl = 'https://www.youtube.com'.$videourls;
$citeurl =  $dom_5[".itemHeadBlock"]->find('a')->attr('href');
$html_6 = file_get_contents($citeurl);
$dom_6 = phpQuery::newDocument($html_6);
$tracklist = $dom_6[".disc"]->find('.title')->text();


//URL設定
if(strpos($urls,'http') === false){
    $url = 'http://'.$urls;
}else{
    $url = $urls;
}


//データ登録sql
$stmt = $pdo->prepare("INSERT INTO gs_bm2_table(name,album,url,tracklist,comment,indate,imageurl,videoimage,videourl,user_num,user_name)VALUES(:name, :album , :url , :tracklist, :comment , sysdate(),:imageurl,:videoimage,:videourl,:user_num,:user_name)");

$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':album', $album, PDO::PARAM_STR);
$stmt->bindValue(':url', $url, PDO::PARAM_STR);
$stmt->bindValue(':tracklist', $tracklist, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':imageurl',$imageurl,PDO::PARAM_STR);
$stmt->bindValue(':videoimage',$videoimage,PDO::PARAM_STR);
$stmt->bindValue(':videourl',$videourl,PDO::PARAM_STR);
$stmt->bindValue(':user_num',$user_num,PDO::PARAM_STR);
$stmt->bindValue(':user_name',$user_name,PDO::PARAM_STR);
$status = $stmt->execute();

//登録後
if($status==false){
    $error = $stmt->errorInfo();
    exit("SQLエラー:".$error[2]);
}else{
    header("Location: discmark.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Discmark_w</title>
</head>
<body>
</body>
</html>