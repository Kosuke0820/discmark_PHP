<?php
session_start();

include("discmark_function.php");
ssidChk();
$dropdown = ownerChk();

$artistName = $_GET['artist'];
$artistNames = urlencode($artistName);

//lastfm api 定義
require_once('lastfm/src/lastfm.api.php');

$caller = new CallerFactory();
$curlCaller = $caller->getCurlCaller();
$curlCaller->setApiKey('8b12b124bc6266b7cc8947fce2501876');

//iTunes API 検索
$apiUrl = 'http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsSearch?country=jp&media=music&entity=musicArtist&limit=14&term='. $artistNames;
//JSON取得
$jsonObj = file_get_contents($apiUrl);				// リクエスト
$json = mb_convert_encoding($jsonObj, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$obj = json_decode($json, true);

if ($obj === NULL) {
    echo "no result...";
    return;
}

$view = '';
$view .= '<div class="result-word"><h2>';
$view .= '<span>Result :  </span>' . $artistName;
$view .= '</h2></div>';

$results = $obj['results'];
$view_2 = '';
$view_3 = '';
if($results == NULL){
    $view_3 .= '<h1 class="noresult">Sorry No Result... </h1>';    
    $view_3 .= '<div class="backbtn"><a class="btn btn-outline-info" href="discmark_user_search.php">Back</a></div>';    
}else{
foreach($results as $result){
    $artistName_2 = $result['artistName'];  
    // $artistNames_2 = urlencode($artistName_2);
    $str = mb_convert_encoding($artistName_2, "utf-8", "auto");
    $an = Artist::getInfo($str);
    
    // var_dump($an);

    $view_2 .= '<a class ="result-box btn btn-outline-info" href="discmark_user_artist.php?artist='.$artistName_2.'">';
    $view_2 .= '<div class="result-artist">';
    if($an->getImage(2) == NULL){
        $view_2 .= '<img src="img/human.png" width="174px" height="174px">';
    }else{
    $view_2 .= '<img src="'.$an->getImage(2).'">';
    }
    $view_2 .= '<p class="artist-name">'.$artistName_2.'</p>';
    $view_2 .= '</div></a>';
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
    .noresult{
        display:block;
        width:800px;
        margin:150px auto 0;
        text-align:center;
        letter-spacing:2px;
        font-size:64px;
    }

    .backbtn{
        cursor:pointer;
        width:800px;
        margin:120px auto 0;
        text-align:center;
    }

    .backbtn a{
        cursor:pointer;
        width:300px;
    }

    .result-word{
        margin:32px;
        text-align:center;
        letter-spacing:2px;
    }

    .result-word span{
       font-size:18px;
    }

    .result-word h2{
        display:inline;
       font-size:40px;
       border-bottom:1px solid white;
    }

    .result-wrapper{
        width:99%;
        display:flex;
        flex-wrap:wrap;
        margin:0 auto;
    }

    .result-box{
        width:202.3px;
        border:none;
        white-space: normal;
    }

    .artist-name{
        color:white;
        font-size:24px;
        margin-top:8px;
        letter-spacing:1.5px;
        line-height:1.1;
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
        <button type="button" class="btn btn-warning dropdown-toggle  dropdown-box" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

<?=$view?>
<div class="result-wrapper">
    <?=$view_2?>
</div>
    <?=$view_3?>
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>
