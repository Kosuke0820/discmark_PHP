<?php
//post取得
$name = filter_input( INPUT_POST, 'name');
$lid = filter_input( INPUT_POST, 'lid');
$lpw = filter_input( INPUT_POST, 'lpw');
$kanri_flg = 0;
$life_flg = 0;

session_start();
include("discmark_function.php");
$pdo = db_con();

//passwordハッシュ化
$pw = password_hash($lpw , PASSWORD_DEFAULT);

//データ登録sql
$stmt = $pdo->prepare("INSERT INTO gs_user_table(name,lid,lpw,kanri_flg,life_flg,indate)VALUES(:name,:lid,:lpw,:kanri_flg,:life_flg ,sysdate())");

$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$stmt->bindValue(':lpw', $pw, PDO::PARAM_STR);
$stmt->bindValue(':kanri_flg', $kanri_flg, PDO::PARAM_INT);
$stmt->bindValue(':life_flg', $life_flg, PDO::PARAM_INT);

$status = $stmt->execute();

//登録後
if($status==false){
    $error = $stmt->errorInfo();
    exit("SQLエラー:".$error[2]);
}else{
    $stmt = $pdo->prepare("SELECT * FROM gs_user_table ORDER BY id DESC LIMIT 1");
    $status = $stmt -> execute();
        if($status==false){
            $error = $stmt->errorInfo();
            exit("SQLエラー:".$error[2]);
        }else{
        $row = $stmt->fetch();
        $_SESSION["chk_ssid"]  = session_id();
        $_SESSION["kanri_flg"] = $row['kanri_flg'];
        $_SESSION["name"]      = $row['name'];
        $_SESSION["id"]        = $row['id'];
        header('Location: discmark_list.php');
        }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Discmark</title>
</head>
<body>
</body>
</html>