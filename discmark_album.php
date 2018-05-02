<?php

include("discmark_function.php");
//DB接続
$pdo = db_con();

$artistName = $_GET['artist'];
$albumName = $_GET['album'];
$upc = $_GET['upc'];

//データ登録SQL
$stmt = $pdo->prepare("SELECT * FROM gs_bm2_table ORDER BY id DESC");
$status = $stmt -> execute();

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
    $view_3 .= '<a href ="discmark_artist.php?artist='.$artistNames.'">';    
    $view_3 .= '<div class="similars-box btn btn-outline-info">';
    if($item -> getImage(2) == NULL){
        $view_3 .= '<img src="img/human.png" width="200px">';
    }else{
        $view_3 .= '<img src="'.$item -> getImage(2).'" width="200px">';
    }
	$view_3 .= '<p>'.$item -> getName().'</p></div></a>';
}

//アーティストメイン画像表示
$view_4 = '';
$view_4 .= '<a class="artist-main btn btn-outline-info" href ="discmark_artist.php?artist='.$artistInfo->getName().'">';
$view_4 .= "<div class='artist'>";
$view_4 .=  '<img src="'.$artistInfo->getImage(4).'" width="250px">';
$view_4 .=  '<h1>'.$artistInfo->getName().'</h1>';
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

    .artist-main{
        border:none;
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

    .header-buttons{
        position:absolute;
        top:12px;
        right:24px;
    }

    .inputs{
        display:flex;
        flex-direction:column;
    }


    input[type="text"] , input[type="password"] {
    width: 300px;
    height:60px;
    font-size:32px;
    padding: 5px;
    border-radius:8px;
    }
    a:hover{
        text-decoration:none;
    }

    input[type="text"]:focus , input[type="password"]:focus{
        outline: 0;
        border:2px solid rgb(0,165,187);
    }

    .form-inner p{
        text-align:left;
        margin-bottom:0px;
    }

    .form-inner input{
        width:600px;
        height:100px;
        font-size:48px;
    }

    .submit-outer{
        margin:16px auto 0;
        padding-top:16px;
    }

    .submit-outer input{
        width:600px;
    }

    .required-message{
        text-align:right;
        opacity:0.5;
        font-size:12px;
    }

    .submit{
        text-align:center;
    }


    #mask{
        position:fixed;
        top:0;
        left:0;
        right:0;
        bottom:0;
        z-index:10;
        background:rgba(25,25,25,0.7);
    }

    #sub-inputs{
        position:fixed;
        top:0;
        left:0;
        right:0;
        bottom:0;
        width:500px;
        height:500px;
        border-radius:16px;
        z-index:11;
        background:rgba(255,255,255,0.8);
        margin:auto;
        color:black;
        padding:48px 8px;
    }

    #subform{
        width:320px;
        margin:24px auto 8px;
    }

    #sub-login{
        width:310px;
        margin:16px auto;
        display:block;
        text-align:center;
    }

    .sub-btn button{
        width:310px;
    }

    #sub-close{
        position:absolute;
        top:8px;
        right:16px;
        display:inline;
        cursor:pointer;
        font-size:24px;
    }

    .login-popup{
        display:none;
    }

    .form-inline{
        position:absolute;
        top:11px;
        right:270px;
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
        border:1px solid rgb(248,193,51);
    }

    .btn{
        cursor:pointer;
    }

    input[type=text]:valid {
    color: black;
}

input[type=text]:invalid {
    color: gray;
}
    </style>
</head>
<body class='bg-dark text-white'>
<header class="bg-white navbar-light bg-light" >
    <a href="discmark_search.php" class="navbar-brand ml-4 mt-1"><img src="img/discmark_logo.png" alt="" height="40px;"></a>
    <form class="form-inline" action="discmark_search_result.php" method="get">
    <input class="search-box mr-sm-2" type="search" placeholder="Artist name..." aria-label="Search" name="artist">
    <button class="btn btn-outline-warning my-2 my-sm-0" type="submit">Search</button>
  </form>
    <div class="header-buttons">
    <a href="discmark_user_registration.php"><button class="btn btn-outline-danger mr-1 my-2 my-sm-0" id="signup" type="">Sign Up</button></a>
    <button class="btn btn-outline-info mr-1 my-2 my-sm-0" id="login" type="">Login</button>
    </div>
</header>
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  </body>
  <div class="main">
    <div class="main-left">
        <?=$view?>
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

    <div id="login-popup" class="login-popup">
    <div id="sub-inputs">
         <div class="close" aria-label="Close" id="sub-close"><span aria-hidden="true">&times;</span></div>
    <h1 class="text-center">Enter Your Information</h1>
        <form name="form1" action="discmark_user_login_act.php" method="post" id="subform">
                <p class="inputs">ID : <input type="text" name="lid" pattern="^([a-zA-Z0-9]{8,})$" required title="英数字8文字以上で入力してください"><span class='required-message'>＊英数字8文字以上</span></p>
                <p class="inputs">Password : <input type="password" name="lpw" pattern="^([a-zA-Z0-9]{8,})$" required title="英数字8文字以上で入力してください"><span class='required-message'>＊英数字8文字以上</span></p>
                <input type="submit" value="Login" class="submit btn btn-info mr-1" id="sub-login">
        </form>
        <div class="text-center sub-btn ml-2">
                    <a href="discmark_user_registration.php"><button class="btn btn-outline-danger mr-1 my-2 my-sm-0" type="" id="signup">Sign Up</button></a>
        </div>
    </div>
        <div id="mask"</div>
</div>
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
    $(function(){
        $("#login").on("click",function(){
            $("#login-popup").css("display","block");
        });

        $("#sub-close ,#signup").on("click",function(){
        $('#login-popup').css('display', 'none');
        });
        })
  </script>
</body>
</html>
