<?php
session_start();

include("discmark_function.php");
ssidChk();
$dropdown = ownerChk();


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
    main{
        padding-top:150px;
    }

  form{
      width:600px;
      margin:80px auto 0;
  }

    .btn{
        cursor:pointer;
    }

    .main-text{
        font-size:64px;
        text-align:center;
    }

    .forms{
        text-align:center;
    }

    .form-inner{
        margin:56px auto 0;
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
        padding:64px 8px;
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
        width:150px;
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

    </style>
</head>
<body class='bg-dark text-white'>
<header class="bg-white navbar-light bg-light" >
    <a href="discmark_user_search.php" class="navbar-brand ml-4 mt-1"><img src="img/discmark_logo.png" alt="" height="40px;"></a>
    <div class="btn-group">
    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span class="fa fa-user-circle"></span><?=$_SESSION['name']?>
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <?=$dropdown?>
            <a href="discmark_list.php" class="text-dark"><button class="dropdown-item" type="button"><span class="fa fa-bullseye"></span>　My Collection</button></a>
            <a href="discmark_user_users.php" class="text-dark"><button class="dropdown-item" type="button"><span class="fa fa-users"></span>　Other Users</button></a>
        <div class="dropdown-divider"></div>
        <a href="discmark_logout.php"><button class="btn btn-outline-warning dropdown-btn" type="button">Logout</button></a>
    </div>
</div>
</header>

<main>
<h1 class='main-text'>Enter your favorite artists</h1>
<form action="discmark_user_search_result.php" method="get" class='forms'>
    <div class='form-inner'>
        <p class="main-input">Artist name :</p><input type="text" name="artist" autofocus required>
    </div>
    <div class='submit-outer'>
        <input type="submit" value="Search" class="submit　submit btn btn-info">
    </div>
</form>
</main>

<div id="login-popup" class="login-popup">
    <div id="sub-inputs">
         <div class="close" aria-label="Close" id="sub-close"><span aria-hidden="true">&times;</span></div>
    <h1 class="text-center">Enter Your Information</h1>
        <form name="form1" action="discmark_user_login_act.php" method="post" id="subform">
                <p class="inputs">ID : <input type="text" name="lid" pattern="^([a-zA-Z0-9]{8,})$" required><span class='required-message'>＊英数字8文字以上</span></p>
                <p class="inputs">Password : <input type="password" name="lpw" pattern="^([a-zA-Z0-9]{8,})$" required><span class='required-message'>＊英数字8文字以上</span></p>
                <input type="submit" value="Login" class="submit btn btn-info mr-1" id="sub-login">
        </form>
        <div class="text-center sub-btn ml-2">
                    <button class="btn btn-outline-warning mr-1 my-2 my-sm-0" type="">Forget?</button>
                    <button class="btn btn-outline-danger mr-1 my-2 my-sm-0" type="" id="signup">Sign Up</button>
        </div>
    </div>
        <div id="mask"</div>
</div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
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