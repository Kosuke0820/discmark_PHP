<?php
session_start();

include("discmark_function.php");
ssidChk();
$dropdown = ownerChk();
$pdo = db_con();

if($_SESSION['kanri_flg'] != 1){
    exit('このページは管理人のみアクセスできます。');
}

$stmt = $pdo->prepare("SELECT * FROM gs_user_table");
$status = $stmt -> execute();

$view = "";
if($status == false){
    queryError($stmt);
}else{
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
        if($result['kanri_flg'] === "0"){
            $kanri = '管理者';
        }else if($result['kanri_flg'] === "1"){
            $kanri = 'スーパー管理者';
        }

        if($result['life_flg'] === "0"){
            $life = '使用中';
        }else if($result['life_flg'] === "1"){
            $life = '退会済み';
        }

        $view .= '<tr><th class="edit"><a class="fa fa-pencil btn btn-outline-danger" href=discmark_user_update.php?id='.$result['id'].'></a>';
        $view .= '<a class="fa fa-trash-o  btn btn-outline-info" href=discmark_user_delete.php?id='.$result['id'].'></a></th>';
        $view .= '<th class="id">'.$result['id'].'</th>';
        $view .= '<th>'.$result['name'].'</th>';
        $view .= '<th>'.$result['lid'].'</th>';
        $view .= '<th>'.$result['lpw'].'</th>';
        $view .= '<th>'.$kanri.'</th>';
        $view .= '<th>'.$life.'</th>';
        $view .= '<th>'.$result['indate'].'</th></tr>';   
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">    
    <style>

    header{
        height:64px;
    }
    .btn,.btn-lg{
        cursor:pointer;
    }

    .btn-group{
        position:absolute;
        top:11px;
        right:16px;
    }

    .dropdown-item{
        cursor:pointer;
    }

    .dropdown-menu a{
        text-decoration:none;
    }

    .dropdown-btn{
        width:90%;
        margin-left: 8px;
    }

    .fa-user-circle{
        margin-right:8px;
        font-size:20px;
    }

    h1{
        margin:8px 0;
    }

    .id{
        width:4%;
        text-align:center;
    }

    .lpw{
        width:20%;
    }

    .edit{
        width:7%;
    }

    .fa-trash-o{
        margin-left:8px;
    }

    .main-table{
        overflow-x: hidden;
        overflow-y: auto;
        -ms-overflow-x: hidden;
        -ms-overflow-y: auto;
        position: absolute;
        display:block;
        height: 670px;
        width:99%;
        margin-left:8px;
        border-collapse: collapse;
        table-layout: fixed;
        word-wrap: break-word;
    }

    .main-bottom{
        position:absolute;
        bottom:8px;
        right:8px;
        width:320px;
    }

    .main-bottom a{
        text-decoration:none;
    }


    .form-inline{
        position:absolute;
        top:11px;
        right:236px;
    }

    .search-box{
        width:160px;
        height:40px;  
        border-radius:5px;
        border:1px solid lightgray;
        padding:8px;
        font-size:17px;
    }

    .search-box:focus{
        outline: 0;
        border:1px solid rgb(0,165,187);
    }

    .selectbtn{
        position:absolute;
        top:400px;
        right:30px;
    }

    .dropdown-box{
        max-width:200px;
        overflow:hidden;
    }
 

    th.header { 
    background-image: url(img/bg.png); 
    background-repeat: no-repeat; 
    background-position: center right; 
    }
    th.headerSortUp { 
        background-image: url(img/asc.png); 
    }
    th.headerSortDown { 
        background-image: url(img/desc.png); 
    }
    </style>
</head>
<body class='bg-dark text-white'>
<header class="bg-white navbar-light bg-light" >
<a href="discmark_user_search.php" class="navbar-brand ml-4 mt-1"><img src="img/discmark_logo.png" alt="" height="40px;"></a>
<form class="form-inline" action="discmark_user_search_result.php" method="get">
      <input class="search-box mr-sm-2" type="search" placeholder="Artist name..." aria-label="Search" name="artist">
      <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Search</button>
    </form>
    <div class="btn-group">
        <button type="button" class="btn btn-warning dropdown-toggle dropdown-box" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fa fa-user-circle"></span><?=$_SESSION['name']?>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <?=$dropdown?>
            <a href="discmark_list.php" class="text-dark"><button class="dropdown-item" type="button"><span class="fa fa-bullseye"></span>　My Collection</button></a>
            <a href="discmark_user_users.php" class="text-dark"><button class="dropdown-item" type="button"><span class="fa fa-users"></span>　Other Users</button></a>
            <a href="discmark_user_search.php" class="text-dark"><button class="dropdown-item" type="button"><span class="fa fa-search"></span>　Artist Search</button></a>
            <div class="dropdown-divider"></div>
            <a href="discmark_logout.php"><button class="btn btn-outline-warning dropdown-btn" type="button">Logout</button></a>
        </div>
    </div>
</header>

<main class='mx-auto'>
<h1 class='text-center'>All Users</h1>

</main>

<div class="result">
<table border="1" cellspacing="0" cellpadding="5" class='main-table' id="users">
    <thead>
        <tr class='text-center t-head'>
        <th class='edit'></th>
        <th class='id'>ID</th>
        <th class='name'>名前</th>
        <th class='lid'>ログイン ID</th>
        <th class='lpw'>ログインパスワード</th>
        <th class='kanri'>管理フラグ</th>
        <th class='life'>ライフフラグ</th>
        <th class='indate'>登録日時</th>
        </tr>
    </thead>
    <tbody>
    <?=$view?>
    </tbody>
</table>
</div>
<div class='main-bottom'>
</div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script src="js/jquery_tablesorter_min.js"></script>
    <script>
    $(function() {
        $('#users').tablesorter({
           headers: {
             0: {sorter:false}
           }
        });
      });
    </script>
</body>
</html>
