<?php

$id = $_GET["id"];

//function読み込み
include("discmark_function.php");

//DB接続
$pdo = db_con();

//データ削除
$stmt = $pdo->prepare("DELETE FROM discmark_bm_table WHERE id=:id");
$stmt->bindValue(':id',$id ,PDO::PARAM_INT);
$status = $stmt->execute();

//削除後
if($status==false){
$error = $stmt->errorInfo();
exit("QueryError:".$error[2]);
}else{
$row = $stmt->fetch();
header("Location: discmark_list.php");
exit();
}