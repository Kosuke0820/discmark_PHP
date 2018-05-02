<?php
session_start();

$lid = $_POST['lid'];
$lpw = $_POST['lpw'];

//function読み込み
include("discmark_function.php");

//DB接続
$pdo = db_con();

//データ登録SQL
$stmt = $pdo->prepare("SELECT * FROM gs_user_table WHERE lid=:lid AND life_flg=0");
$stmt->bindValue(":lid", $lid);
$res = $stmt->execute();

//SQLエラー時の処理
if($res==false){
    queryError($stmt);
}

//該当レコードをSESSIONに代入
$val = $stmt->fetch();

if(password_verify($lpw, $val['lpw']) && $val["kanri_flg"] == 1){
    $_SESSION["chk_ssid"]  = session_id();
    $_SESSION["kanri_flg"] = $val['kanri_flg'];
    $_SESSION["name"]      = $val['name'];
    $_SESSION["lid"]      = $val['lid'];
    $_SESSION["id"]      = $val['id'];
    header('Location: discmark_user_list.php');
}else if(password_verify($lpw, $val['lpw'])){
    $_SESSION["chk_ssid"]  = session_id();
    $_SESSION["kanri_flg"] = $val['kanri_flg'];
    $_SESSION["name"]      = $val['name'];
    $_SESSION["lid"]      = $val['lid'];    
    $_SESSION["id"]      = $val['id'];
    header('Location: discmark_list.php');
}else{
    header("Location: discmark_user_login.php?error");
}
exit();

?>