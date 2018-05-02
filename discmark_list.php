<?php

session_start();

include("discmark_function.php");
ssidChk();
$dropdown = ownerChk();
$pdo = db_con();

 if(!isset($_GET['id'])){
    $getid = $_SESSION['id'];
 }else{
    $getid = $_GET['id'];
 }

$view_3 = '';
$view_4 = '';
 if($getid == $_SESSION['id']){
    $view_3 .= '<p><a href="#" id="upload" class="btn btn-outline-info">Change Images</a></p>';
    $view_4 .='<p id="selectbtn" class="selectbtn un-pushed btn btn-outline-danger">Select the disc you want to trash?</p>';  
 }else{
    $view_3 = '';
    $view_4 = '';    
 }

$stmt = $pdo->prepare("SELECT * FROM discmark_bm_table WHERE user_id=:user_id");
$stmt->bindValue(':user_id', $getid, PDO::PARAM_INT);
$status = $stmt -> execute();

$stmt2 = $pdo->prepare("SELECT * FROM gs_user_table WHERE id=:id");
$stmt2->bindValue(':id', $getid, PDO::PARAM_INT);
$status2 = $stmt2 -> execute();

$view_2 = '';
if($status2 == false){
    queryError($stmt2);
}else{
    $result_2 =   $stmt2->fetch(PDO::FETCH_ASSOC);
        if($result_2['image_url'] == NULL){
            $view_2 .= '<img src="img/human.png" width="200px" height="200px">';
        }else{
            $view_2 .= '<img src="'.$result_2['image_url'].'" width="200px" height="200px">';            
        }
}
//lastfm API
require_once('lastfm/src/lastfm.api.php');

$caller = new CallerFactory();
$curlCaller = $caller->getCurlCaller();
$curlCaller->setApiKey('8b12b124bc6266b7cc8947fce2501876');

$view = "";
if($status == false){
    queryError($stmt);
}else{
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
        $albumInfo = Album::getInfo($result['artist_name'],$result['album_name']);
        $albumNames = urlencode($result["album_name"]);        
        $view .= '<a class="discurl" href ="discmark_user_album.php?upc='.$result["upc"].'&artist='.$result["artist_name"].'&album='.$albumNames.'">';
        $view .= '<div class="disc-box btn btn-outline-info">';
        if($albumInfo->getImage(2) == NULL){
           $view .= '<img src ="img/record.png" width="174px" height="174px">';        
        }else{
            $view .= '<img src ="'.$albumInfo->getImage(2).'">';
        }
        $view .= '<h4>'.$result['artist_name'].'</h4>';
        $view .= '<p>'.$result['album_name'].'</p>';
        $view .= '<div class="trash-space"><a href="discmark_t.php?id='.$result["id"].'" class="trash-btn btn btn-outline-danger fa fa-trash"></a></div>';
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

    .main-wrap{
        display:flex;
        margin:40px 0 40px 400px;
        letter-spacing:2px;
    }

    .main-left img{
        border-radius:300px;
    }   

    .main-right{
        margin-left:120px;
        font-size:24px;
        margin-top:32px;
    }

    .main-right h2{
        font-size:48px;
        font-weight:bold;
        margin-bottom:40px;
    }

    .main-right span{
        font-size:28px;
        font-weight:lighter;
    }

    .words{
        text-align:center;
        font-size:48px;
        letter-spacing:1px;
    }


    .album-wrap{
        display:flex;
        flex-wrap:wrap;
        width:98%;
        margin:32px auto 100px;
    }

    .disc-box{
        width:200px;
        text-align:center;
        margin-left:1px;
        color:white;
        border:none;
        white-space: normal;
        letter-spacing:1px;
    }
    
    .disc-box h4{
        margin-top:8px;
    }

    .discurl{
        color:white;
    }

    .discurl:hover{
        color:white;
        text-decoration:none;
    }
    .trash-space{
        text-align:right;
        display:none;
    }

    .upload-wrap{
        position:absolute;
        top:300px;
        left:280px;
        width:143px;
    }

    #upload{
        font-size:16px;
        height:40px;
        margin-top:auto;
    }

    #image_file{
        display:none;
    }

    #upload_btn {
        display: none;
        width:143px;
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
            <!-- <a href="discmark_list.php" class="text-dark"><button class="dropdown-item" type="button"><span class="fa fa-bullseye"></span>　My Collection</button></a> -->
            <a href="discmark_user_users.php" class="text-dark"><button class="dropdown-item" type="button"><span class="fa fa-users"></span>　Other Users</button></a>
            <a href="discmark_user_search.php" class="text-dark"><button class="dropdown-item" type="button"><span class="fa fa-search"></span>　Artist Search</button></a>
            <div class="dropdown-divider"></div>
            <a href="discmark_logout.php"><button class="btn btn-outline-warning dropdown-btn" type="button">Logout</button></a>
        </div>
    </div>
</header>
<div class="main-wrap">
        <div class="upload-wrap">
            <?=$view_3?>
            <form method="post" action="discmark_upload.php" enctype="multipart/form-data">
                <input type="file" accept="image/*" capture="camera" id="image_file" name="upfile" style="opacity:0;">
                <input type="submit" id="upload_btn" class="btn btn-danger" value='Upload'>
            </form>
        </div>
    <div class="main-left">
        <p><?=$view_2?></p>
    </div>
    <div class="main-right">
        <h2><?=$result_2['name']?> <span>さん<spl_autoload_unregister></h2>
        <p>User ID : <?=$result_2['lid']?></p>
        <p id="record_count"></p>
    </div>
</div>
<h2 class="words">Registered albums</h2>
<?=$view_4?>
<div class="album-wrap">
<?=$view?>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script>
    let classCount = $(".disc-box").length;
    $("#record_count").html('登録されたレコード数 : ' + classCount + ' 枚');

    $("#upload").on("click", function() {
            $("#image_file").trigger("click");
        });

    $("#image_file").on("change", function() {
        var file = $("#image_file").get(0).files[0];
        if (file.size / 1024 > 500) {
            alert("サイズが大きすぎます。");
            return false;
        } else {
            console.log("OK");
        }
        $("#upload_btn").show();
    });

    // $(document).ready(function(){

    $("#selectbtn").click(function(){
            if($("#selectbtn").hasClass("un-pushed")){
                    $("#selectbtn").removeClass("un-pushed");
                    $("#selectbtn").removeClass("btn-outline-danger");
                    $("#selectbtn").addClass("btn-danger");
                    $("#selectbtn").text("Close trash box");
                    $(".trash-space").show('fast');
            }else{
                    $("#selectbtn").addClass("un-pushed");
                    $("#selectbtn").removeClass("btn-danger");
                    $("#selectbtn").addClass("btn-outline-danger");
                    $("#selectbtn").text("Select the disc you want to trash?");
                    $(".trash-space").hide('fast');
            }
    })
    // })
    </script>
</body>
</html>
