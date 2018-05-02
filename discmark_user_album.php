<?php

session_start();

$artistName = $_GET['artist'];
$albumName = $_GET['album'];
$upc = $_GET['upc'];

include("discmark_function.php");
ssidChk();
$dropdown = ownerChk();
$pdo = db_con();

//お気に入りに入れるか判定
$stmt = $pdo->prepare("SELECT * FROM discmark_bm_table WHERE user_id=:user_id AND upc=:upc");
$stmt->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
$stmt->bindValue(':upc', $upc, PDO::PARAM_INT);
$status = $stmt -> execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);
$view_5 = '';
if($result == false){
    $view_5 .= '<div class="register-btn">';
    $view_5 .= '<p class="btn btn-outline-info fa fa-plus" id="register-btn"></p></div>';
}else{
    $view_5 .= '<div class="register-btn">';
    $view_5 .= '<p class="btn btn-info fa fa-check" id="registered"></p></div>';
}

//他登録ユーザー表示
$stmt2 = $pdo->prepare("SELECT * FROM discmark_bm_table WHERE upc=:upc AND user_id NOT IN (user_id=:user_id)");
$stmt2->bindValue(':upc', $upc, PDO::PARAM_INT);
$stmt2->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
$status2 = $stmt2 -> execute();
if($status2 == false){
    $view_6 = '';    
}else{
    $view_6 = '';
    $view_6 .= '<div class="similar-user">';
    $view_6 .= '<div class="similar-user-words">';
    $view_6 .= '<h2>Similar Users</h2>';
    $view_6 .= '</div>';
    while($result2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $stmt3 = $pdo->prepare("SELECT * FROM gs_user_table WHERE id=:id");
        $stmt3->bindValue(':id', $result2["user_id"], PDO::PARAM_INT);
        $status3 = $stmt3 -> execute();
        while($result3 = $stmt3->fetch(PDO::FETCH_ASSOC)){
            $view_6 .= '<a class="btn btn-outline-info user-box" href="discmark_list.php?id='.$result3['id'].'">';
                if($result3['image_url'] == NULL){
                    $view_6 .= '<div class="user_image"><img src="img/human.png" alt="" width="200px" height="200px">';            
                }else{
                    $view_6 .= '<div class="user_image"><img src="'.$result3['image_url'].'" alt="" width="200px" height="200px">';
                }
            $view_6 .= '<p>'.$result3['name'].'</p>';
            $view_6 .= '</div></a>';
    }
    }
}


//last.fm API　定義
require_once('lastfm/src/lastfm.api.php');

$caller = new CallerFactory();
$curlCaller = $caller->getCurlCaller();
$curlCaller->setApiKey('8b12b124bc6266b7cc8947fce2501876');

$limit = 5;
$similars = Artist::getSimilar($artistName, $limit);
$artistInfo = Artist::getInfo($artistName);
$albumInfo = Album::getInfo($artistName,$albumName);


//iTunes API(トラックリスト部)定義
$apiUrl = 'http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsLookup?id='.$upc.'&country=JP&entity=song';

//JSON取得
$jsonObj = file_get_contents($apiUrl);				// リクエスト
$json = mb_convert_encoding($jsonObj, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$obj = json_decode($json, true);

if ($obj === NULL) {
	return;
}

$results = $obj['results'];

//アルバムタイトル表示
$view='';
if($albumInfo->getImage(4) == NULL){
    $view .= '<div class="albums">';
    $view .= '<div class="album-image"><image src="'.$results[0]['artworkUrl100'].'" width="300px" height="300px"></div>';
    $view .= '<div class="album-title"><h1>'.$results[0]['collectionName'].'</h1></div>';
    $view .= '</div>';
}else{
    $view .= '<div class="albums">';
    $view .= '<div class="album-image"><image src="'.$albumInfo->getImage(4).'"></div>';
    $view .= '<div class="album-title"><h1>'.$results[0]['collectionName'].'</h1></div>';
    $view .= '</div>';
}



// var_dump($results);
//トラックリスト表示
$view_2='';
for($i = 1 ; $i < count($results); $i++){
	$artworkUrl60		= (string)$results[$i]['artworkUrl60'];	
	$trackName   	= (string)$results[$i]['trackName'];
	$artistName    		= (string)$results[$i]['artistName'];	
	$artistViewUrl    	= (string)$results[$i]['artistViewUrl'];	
	$previewUrl    	= (string)$results[$i]['previewUrl'];
	$trackNumber    	= (string)$results[$i]['trackNumber'];
 
	$view_2 .= '<tr>';
    $view_2 .= '<th class="column_1"><div class="play btn btn-outline-info fa fa-play-circle-o">';    
    $view_2 .= '<audio class=audio preload="auto">';
    $view_2 .= '<source src="'.$previewUrl.'"></audio></div></th>';
    $view_2 .= '<th class="column_2">'.$trackNumber.' .</th>';    
    $view_2 .= '<th class="column_3">'.$trackName.'</th>';    
    $view_2 .= '</tr>';
}

//類似アーティスト表示
$view_3 = '';
foreach($similars as $item){
    $artistNames = urlencode($item -> getName());        
    $view_3 .= '<a href ="discmark_user_artist.php?artist='.$artistNames.'">';    
    $view_3 .= '<div class="similars-box btn btn-outline-info">';
    if($item -> getImage(2) == NULL){
        $view_3 .= '<img src="img/human.png" width="200px">';
    }else{
        $view_3 .= '<img src="'.$item -> getImage(2).'" width="200px">';
    }
	$view_3 .= '<p>'.$item -> getName().'</p></div></a>';
}

$artist_names = $artistInfo->getName();

//アーティストメイン画像表示
$view_4 = '';
$view_4 .= '<a class="btn btn-outline-info main-image" href ="discmark_user_artist.php?artist='.$artistInfo->getName().'">';
$view_4 .= "<div class='artist'>";
$view_4 .=  '<img src="'.$artistInfo->getImage(4).'" width="250px">';
$view_4 .=  '<h1>'.$artist_names.'</h1>';
$view_4 .= "</div></a>";
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
    .main-image{
        border:none;
        margin-top:48px;
    }
    .main{
        display:flex;
    }

    .main-right{
        position:absolute;
        top:250px;
        right:64px;
        text-align:right;
    }

    .albums{
        display:flex;
        margin:64px 0 64px 160px;
    }

    .album-title{
        margin:auto 0 auto 64px;
    }

    .album-title h1{
        width:650px;
    }

    .artist{
        color:white;
        padding-top:16px;
    }

    .main-left h2{
        margin-left:48px;
    }

    table{
        width:800px;
        margin-left:48px;
        border:1px solid gray;
        font-size:24px;
        text-align:center;
    }

    th{
        border-bottom:1px solid gray;
    }


    .column_1{
        width:4%;
    }

    .column2{
        width:8%;
        text-align:center;
    }

    .column_3{
        width:87%;
        text-align:left;
        letter-spacing:2px;
    }

    .play{
        cursor:pointer;
        font-size:32px;
        border:none;
        border-radius:200px;
    }

    .similarArtist{
        display:flex;
        justify-content:space-around;
        padding:96px 0 64px;
    }

    .similarArtist h2{
        margin:auto 0;
        text-align:center;
        padding:0 24px;
        border-bottom:1px solid white;
    }

    .similars-box{
        text-align:center;
        border:none;
    }

    .similars-box a{
        text-decoration:none;
    }
    .similars-box p{
        margin-top:16px;
        font-size:20px;
        color:white;
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
    .register-btn{
        position:absolute;
        top:80px;
        left:40px;
    }
    .register-btn p{
        width:80px;
        height:50px;
        font-size:38px;
    }

    #ajax-success{
        margin:24px 0 -24px 150px; 
        opacity:0.9;       
    }

    #ajax-success h3{
        letter-spacing:2px;     
    }

    .similar-user{
        display:flex;
        flex-wrap:wrap;
        margin-bottom:64px;
    }

    .similar-user h2{
        width:230px;
        margin:0 auto;
        border-bottom:1px solid white;
    }

    .similar-user p{
        font-size:18px;

    }

    .similar-user-words{
        width:250px;
        text-align:center;
        margin:auto 0;
    }

    .user-box{
        width:224px;
        border:none;
        color:white;
        margin-left:16px;
    }
    .user-box p{
        margin-top:8px;
        letter-spacing:1px;
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
  <div class="main">
    <div class="main-left">
        <div id="ajax-success"></div>
        <?=$view?>
        <?=$view_5?>
        <h2>Tracklist</h2>
        <table>
            <tbody>
            <?=$view_2?>
            </tbody>
        </table>
    </div>
    <div class="main-right">
        <?=$view_4?>
    </div>
  </div>
  <div class="similarArtist">
  <h2>Similar Artists</h2>
  <?=$view_3?>
    </div>
        <?=$view_6?>
    </div>        
    <input id = "user_id" type="hidden" value="<?=$_SESSION['id']?>">
    <input id = "user_name" type="hidden" value="<?=$_SESSION['name']?>">
    <input id = "artist_name" type="hidden" value="<?=$artist_names?>">
    <input id = "album_name" type="hidden" value="<?=$results[0]['collectionName']?>">
    <input id = "upc" type="hidden" value="<?=$upc?>">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
      </body>
      <script>
      let classCount = $('.audio').length;
  $(".play").on('click',function(){
    if($(this).hasClass('fa-play-circle-o')){
            $('.play').removeClass('fa-pause-circle-o');
            $('.play').addClass('fa-play-circle-o');
            $(this).removeClass('fa-play-circle-o');
            $(this).addClass('fa-pause-circle-o');
            for(let i = 0; i < classCount;i++){
                $('audio').get(i).pause();
            }
            $(this).children('audio').get(0).play();
      }else{
            $(this).removeClass('fa-pause-circle-o');
            $(this).addClass('fa-play-circle-o');
            $(this).children('audio').get(0).pause();
      }
})

    $("#registered").on('click',function(){
        $("#ajax-success").html("<h3>Already registered</h3>")
    })

    $("#register-btn").on('click',function(){
        $("#register-btn").removeClass('btn-outline-info');
        $("#register-btn").removeClass('fa-plus');
        $("#register-btn").addClass('btn-info');
        $("#register-btn").addClass('fa-check');
        $.ajax({
                type: "get",
                url: "ajax_3.php",
                data: {
                    id: $("#user_id").val(),
                    name: $("#user_name").val(),
                    artistName: $("#artist_name").val(),
                    albumName: $("#album_name").val(),
                    upc: $("#upc").val(),
                },
                dataType: "html",
                success: function(data) {
                  if(data=="false"){
                    alert("エラー");
                  }else{
                    $("#ajax-success").html("<h3>Saved in favorites</h3>")
                  }
                }
            });
    })
  </script>
</body>
</html>
