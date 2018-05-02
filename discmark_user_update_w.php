<?php
//post取得
$id = filter_input( INPUT_POST, 'id');
$name = filter_input( INPUT_POST, 'name');
$lid = filter_input( INPUT_POST, 'lid');
$lpw = filter_input( INPUT_POST, 'lpw');
$kanri_flg = filter_input( INPUT_POST, 'kanri_flg');
$life_flg = filter_input( INPUT_POST, 'life_flg');


include("discmark_function.php");
$pdo = db_con();

//passwordハッシュ化
$pw = password_hash($lpw , PASSWORD_DEFAULT);


//データ登録sql
$stmt = $pdo->prepare("UPDATE gs_user_table SET name=:name, lid=:lid, lpw=:lpw, kanri_flg=:kanri_flg,life_flg=:life_flg WHERE id=:id");

$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$stmt->bindValue(':lpw', $pw, PDO::PARAM_STR);
$stmt->bindValue(':kanri_flg', $kanri_flg, PDO::PARAM_INT);
$stmt->bindValue(':life_flg', $life_flg, PDO::PARAM_INT);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

//登録後
if($status==false){
    $error = $stmt->errorInfo();
    exit("SQLエラー:".$error[2]);
}else{
    header("Location: discmark_user_list.php");
    exit();
}

?>