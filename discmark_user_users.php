<?php
session_start();

include("discmark_function.php");
ssidChk();
$dropdown = ownerChk();

$pdo = db_con();

$stmt = $pdo->prepare("SELECT * FROM gs_user_table");
$status = $stmt -> execute();

$view = "";
if($status == false){
    queryError($stmt);
}else{
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
        $view .= '<a class="user-box-outer" href=discmark_list.php?id='.$result['id'].'>';
        $view .= '<div class="user-box btn btn-outline-info">';
        if($result['image_url'] == NULL){
            $view .= '<img src="img/human.png" width="174px" height="174px">';
        }else{
            $view .= '<img src="'.$result["image_url"].'" width="174px" height="174px">';
        }
        $view .= '<p>'.$result['name'].'</p>';
        $view .= '</div></a>';
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
    h1{
        margin:8px 0;
    }

    header{
        height:64px;
    }
 

    /* ドロップダウンメニューcss */
    .btn-group{
        position:absolute;
        top:11px;
        right:16px;
    }

    .btn{
        cursor:pointer;
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

    /* ここまで */

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


    .header-title{
        text-align:center;
        margin:32px 0;
    }

    .header-title span{
        font-size:18px;
    }

    .header-title h2{
        display:inline;
       font-size:40px;
       border-bottom:1px solid white;
       letter-spacing:1px;
    }

    .users-area{
        display:flex;
        flex-wrap:wrap;
        width:99%;
        margin:0 auto;
        padding:8px 0;
    }

    .user-box{
        border:none;
        color:white;
        width:202px;
        white-space: normal;
        word-wrap: break-word;
    }

    .user-box p{
        font-size:18px;
        letter-spacing:2px;
        margin-top:8px;
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
            <a href="discmark_user_search.php" class="text-dark"><button class="dropdown-item" type="button"><span class="fa fa-search"></span>　Artist Search</button></a>
            <div class="dropdown-divider"></div>
            <a href="discmark_logout.php"><button class="btn btn-outline-warning dropdown-btn" type="button">Logout</button></a>
        </div>
    </div>
</header>
    <div class="header-title">
        <h2><span>Discmark :  </span>All Users</h2>
    </div>
    <div class="users-area">
        <?=$view?>
    </div>
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>
