<?php

$id = $_GET["id"];


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

//データ削除
$stmt = $pdo->prepare("DELETE FROM gs_user_table WHERE id=:id");
$stmt->bindValue(':id',$id ,PDO::PARAM_INT);
$status = $stmt->execute();

//削除後
if($status==false){
$error = $stmt->errorInfo();
exit("QueryError:".$error[2]);
}else{
$row = $stmt->fetch();
header("Location: discmark_user_list.php");
exit();
}