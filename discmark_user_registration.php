<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ユーザー登録</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <style>
    header{
        height:64px;
    }
    main{
        padding-top:80px;
    }
    .btn{
        cursor:pointer;
    }

    .form-inner{
        width:320px;
        margin:56px auto 0;
    }

    .inputs{
        margin:10px;
        display:flex;
        flex-direction:column;
    }

    .submit-outer{
        width:320px;
        margin:0 auto;
        text-align:right;
        padding-top:16px;
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

    .required-message{
        text-align:right;
        opacity:0.5;
        font-size:12px;
    }

    .submit{
        text-align:right;
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

input[type=text]:valid {
    color: black;
}

input[type=text]:invalid {
    color: gray;
}

.form-inline{
        position:absolute;
        top:11px;
        right:150px;
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



    </style>
</head>
<body class='bg-dark text-white'>
<header class="bg-white navbar navbar-expand-lg navbar-light bg-light" >
    <a href="discmark_search.php" class="navbar-brand mr-auto"><img src="img/discmark_logo.png" alt="" height="40px;"></a>
    <form class="form-inline" action="discmark_search_result.php" method="get">
    <input class="search-box mr-sm-2" type="search" placeholder="Artist name..." aria-label="Search" name="artist">
    <button class="btn btn-outline-warning my-2 my-sm-0" type="submit">Search</button>
  </form>
    <div class="ml-auto">
    <button class="btn btn-outline-info mr-1 my-2 my-sm-0" id="login" type="">Login</button>
    </div>
</header>

<main>
<h1 class='text-center'>User Registration</h1>
<form action="discmark_user_resistration_w.php" method="post">
    <div class='form-inner'>
        <p class="inputs">Name : <input type="text" name="name" maxlength="16"　autofocus required><span class='required-message'>＊16文字以内</span></p>
        <p class="inputs">ID : <input type="text" name="lid" pattern="^([a-zA-Z0-9]{8,})$" required title="英数字8文字以上で入力してください"><span class='required-message'>＊英数字8文字以上</span></p>
        <p class="inputs">Password : <input type="text" name="lpw" pattern="^([a-zA-Z0-9]{8,})$" required title="英数字8文字以上で入力してください"><span class='required-message'>＊英数字8文字以上</span></p>
    </div>
    <div class='submit-outer'>
        <input type="submit" value="Submit" class="submit　submit btn btn-danger">
    </div>
</form>
</main>

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