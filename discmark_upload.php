<?php

session_start();

include("discmark_function.php");
ssidChk();
$dropdown = ownerChk();
$pdo = db_con();


if (isset($_FILES["upfile"] ) && $_FILES["upfile"]["error"] ==0 ) {
$file_name = $_FILES["upfile"]["name"];
$tmp_path = $_FILES["upfile"]["tmp_name"];

$extension = pathinfo($file_name, PATHINFO_EXTENSION);
$file_name = date("YmdHis").md5(session_id()) . "." . $extension;

$file_dir_path = "upload/".$file_name;
$img="";

if(is_uploaded_file($tmp_path)){
    if(move_uploaded_file($tmp_path,$file_dir_path)){
        chmod($file_dir_path, 0644);
        $stmt = $pdo->prepare("UPDATE gs_user_table SET image_url=:image_url WHERE id=:id");
        $stmt->bindValue(':image_url', $file_dir_path, PDO::PARAM_STR);        
        $stmt->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
        $status = $stmt->execute();
        if($status==false){
            $error = $stmt->errorInfo();
            exit("SQLエラー:".$error[2]);
        }else{
            header("Location: discmark_list.php");
            exit();
        }
    };
 }else{
     $img = "画像が送信されていません";
 }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<main>
    <!-- ヘッダー -->
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header"><a class="navbar-brand" href="file_view.php">写真アップロード</a></div>
            </div>
        </nav>
    </header>
</main>
<script src="js/jquery-2.1.3.min.js"></script>
</body>
</html>