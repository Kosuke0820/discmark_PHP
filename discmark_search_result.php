<?php

include("discmark_function.php");
//DB接続
$pdo = db_con();

//データ登録SQL
$stmt = $pdo->prepare("SELECT * FROM gs_bm2_table ORDER BY id DESC");
$status = $stmt -> execute();

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
    $view_3 .= '<div class="backbtn"><a class="btn btn-outline-info" href="discmark_search.php">Back</a></div>';    
}else{
foreach($results as $result){
    $artistName    		= (string)$result['artistName'];
    $an = Artist::getInfo($artistName);

    $view_2 .= '<a class ="result-box btn btn-outline-info" href="discmark_artist.php?artist='.$artistName.'">';
    $view_2 .= '<div class="result-artist">';
    if($an->getImage(2) == NULL){
        $view_2 .= '<img src="img/human.png" width="174px" height="174px">';
    }else{
    $view_2 .= '<img src="'.$an->getImage(2).'">';
    }
    $view_2 .= '<p class="artist-name">'.$artistName.'</p>';
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

    .btn{
        cursor:pointer;
    }

    .header-buttons{
        position:absolute;
        top:12px;
        right:24px;
    }

    .header-buttons button{
        cursor:pointer;
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

<?=$view?>
<div class="result-wrapper">
    <?=$view_2?>
</div>
    <?=$view_3?>
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
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script>
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
