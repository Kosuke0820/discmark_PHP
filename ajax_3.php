<?php

include("discmark_function.php");

/**
 * Ajaxによるリクエストかどうか
 * @return boolean true or false
 */
function isAjax(){
  if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
  {
       return true;
  }else{
       return false;
  }
}

//Ajaxアクセス以外は処理しない。
if( isAjax()==false ){
  header("HTTP/1.0 404 Not Found");
  exit();
}

//1. POSTデータ取得
$user_id  = $_GET["id"];
$user_name   = $_GET["name"];
$artistName = $_GET["artistName"];
$albumName = $_GET["albumName"];
$upc = $_GET["upc"];

//2. DB接続します(エラー処理追加)
$pdo = db_con();

//３．データ登録SQL作成
$stmt = $pdo->prepare("INSERT INTO discmark_bm_table(user_id, user_name, upc, album_name, artist_name, indate)
VALUES(:a1, :a2, :a3, :a4, :a5, sysdate())");
$stmt->bindValue(':a1', $user_id);
$stmt->bindValue(':a2', $user_name);
$stmt->bindValue(':a3', $upc);
$stmt->bindValue(':a4', $albumName);
$stmt->bindValue(':a5', $artistName);
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  // $error = $stmt->errorInfo();
  // exit("QueryError:".$error[2]);
  echo "false";
}else{
  //５．index.phpへリダイレクト
  echo "true";
}
?>
