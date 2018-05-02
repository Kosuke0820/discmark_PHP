<?php

session_start();

include("discmark_function.php");
ssidChk();
$dropdown = ownerChk();

$artistName = $_GET['artist'];
$artistNames = urlencode($artistName);

//iTunes API
$apiUrl = 'http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsSearch?country=jp&media=music&entity=album&limit=30&term='.$artistNames;
$jsonObj = file_get_contents($apiUrl);				// リクエスト
$json = mb_convert_encoding($jsonObj, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$obj = json_decode($json, true);
if ($obj === NULL) {
	return;
}
$results = $obj['results'];

$view = '';
foreach($results as $result){
	$artworkUrl		= (string)$result['artworkUrl100'];	
	$collectionName 	= (string)$result['collectionName'];	
	$Name    		= (string)$result['artistName'];	
    $collectionId    	= (string)$result['collectionId'];
    
    $collectionNames = urlencode($collectionName);    
 
    $view .= '<a href="discmark_user_album.php?upc='.$collectionId.'&artist='.$artistName.'&album='.$collectionNames.'">';
    $view .= '<div class="album-box btn btn-outline-info">';
	$view .= '<img src="'.$artworkUrl.'">';
	$view .= '<p>'.$collectionName.'</p>';
	$view .= '</div></a>';
    }


    
//lastfm API
require_once('lastfm/src/lastfm.api.php');

$caller = new CallerFactory();
$curlCaller = $caller->getCurlCaller();
$curlCaller->setApiKey('8b12b124bc6266b7cc8947fce2501876');

$limit = 5;
$similars = Artist::getSimilar($artistName, $limit);
$artistInfo = Artist::getInfo($artistName);
if ($artistInfo === NULL) {
    $view_2 = '';
    $view_2 .= "<div class='artist-header'>";
    $view_2 .=  '<img src="img/no_result.jpg" width="200px">';
    $view_2 .=  '<h1>'.$artistName.'</h1>';
    $view_2 .= "</div>";
}else{
//アーティストメイン画像表示
    $view_2 = '';
    $view_2 .= "<div class='artist-header'><div class='artist-left'>";
    if($artistInfo->getImage(4) == NULL){
        $view_2 .= '<img src="img/human_300.png" width="300px" height="300px"></div>';
    }else{
        $view_2 .= "<img src='".$artistInfo->getImage(4)."'></div>";
    }
    $view_2 .= "<div class='main-name'>";
    $view_2 .=  "<h1>".$artistInfo->getName()."</h1></div>";
    $view_2 .= "</div>";
}
//類似アーティスト表示

if ($similars === NULL) {
    return;
}else{
$view_3 = '';
foreach($similars as $item){
    $artistName_2 = urlencode($item -> getName());    
	$view_3 .= '<div class="similars-box btn btn-outline-info">';
    $view_3 .= '<a href ="discmark_user_artist.php?artist='.$artistName_2.'">';
    if($item -> getImage(2) == NULL){
        $view_3 .= '<img src="img/human.png" width="200px">';
    }else{
        $view_3 .= '<img src="'.$item -> getImage(2).'" width="200px">';
    }
	$view_3 .= '<p>'.$item -> getName().'</p></a></div>';
}
}



//Video表示
$apiUrl_2 = 'http://itunes.apple.com/search?country=jp&media=music&entity=musicVideo&limit=2&term='.$artistNames;
$jsonObj_2 = file_get_contents($apiUrl_2);				// リクエスト
$json_2 = mb_convert_encoding($jsonObj_2, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$obj_2 = json_decode($json_2, true);
if ($obj === NULL || $obj_2 === NULL ) {
	return;
}

$results_2 = $obj_2['results'];
$view_4 = '';
foreach($results_2 as $result_2){
    if($result_2['previewUrl'] == NULL){
        continue;
    }
    
    $view_4 .= '<div class="videos">';
    $view_4 .= '<video src="'.$result_2['previewUrl'].'" controls preload></video>';
    $view_4 .= '<p>'.$result_2['trackCensoredName'].'</p>';
    $view_4 .= '</div>';
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

    .artist-header{
        width:100%;
        display:flex;
        justify-content:center;
        margin:48px 0;
    }

    .artist-header h1,.main-name{
        margin:0 120px;
    }

    .artist-left{
        width:50%;
        text-align:right;
        padding-right:150px;
    }

    .main-name{
        width:50%;
        margin:auto 0;
    }
    .main-name h1{
        font-size:80px;
        margin-left:0px;
    }

    .albums{
        text-align:center;
        margin-left:40px;
    }

    .album-box{
        width:170px;
        border:none;
        white-space: normal;
    }

    .album-box p{
        margin-top:16px;
        color:white;
    }

    .album-box a{
        text-decoration:none;
    }

    .sub{
        display:flex;
    }
    .albums{
        display:flex;
        flex-wrap:wrap;
    }
    .album-box-title h1{
        margin-top:32px;
        border-bottom:1px solid white;
    }

    .video-wrap{
        display:flex;
        margin:64px 0;
    }

    .video-word{
        width:24%;
        margin:auto 0 auto;
        text-align:center;
    }

    .video-word h1{
        padding:0 24px;
        display:inline;
        border-bottom:1px solid white;
    }

    .videos{
        /* width:38%; */
        margin:0 64px;
    }

    video{
        /* height:300px; */
        width:400px;
    }

    .similar-wrap{
        display:flex;
        justify-content:space-around;
        padding:32px 0 64px;
    }

    .similar-wrap h1{
        margin:auto 0;
        text-align:center;
        padding:0 24px;
        border-bottom:1px solid white;
    }

    .similars-box{
        text-align:center;
        width:224px;
        white-space: normal;
        border:none;
        font-size:20px;
        letter-spacing:1px;
    }

    .similars-box img{
        margin-bottom:16px;
    }
    .similars-box a{
        color:white;
        text-decoration:none;
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
<div class="main">
    <div class='main-left'>
        <div class='sub'>
            <?=$view_2?>
        </div>
        <div class="albums">
        <div class='album-box album-box-title'>
        <h1>Albums</h1>
        </div>
            <?=$view?>
        </div>
    </div>
    <div class="video-wrap">
        <div class="video-word">
            <h1>Videos</h1>
        </div>
        <?=$view_4?>
    </div>
    <div class="similar-wrap">
    <h1>Similar Artists</h1>
    <?=$view_3?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>
