<?php

session_start();

include("discmark_function.php");
ssidChk();
$dropdown = ownerChk();
$pdo = db_con();


//getの受け取り
$id = $_GET['id'];

//データ抽出
$stmt = $pdo->prepare("SELECT * FROM discmark_bm_table WHERE id=:id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

//データ抽出後
if($status==false){
    $error = $stmt->errorInfo();
    exit("QueryError:".$error[2]);
}else{
    $row = $stmt->fetch();
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
    main{
        width:90%;
        padding-top:48px;
    }
    .main-top{
        display:flex;
        justify-content:space-around;
    }

    .main-top h1{
        margin-top:40px;
    }

    .btn{
        cursor:pointer;
    }

    .form-inner{
        margin-top:24px;
        display:flex;
        justify-content:space-around;
    }

    .inputs{
        margin:16px;
    }

    input[type="text"] {
    width: 300px;
    height:60px;
    font-size:32px;
    padding: 5px;
    border-radius:8px;
    }
    textarea{
    height:260px;
    font-size:24px;
    border-radius:8px;
    resize: none;
    border: 2px solid gray;
    }
    a:hover{
        text-decoration:none;
    }

    .btn-block{
        width:70%;
        margin:0 auto;
    }
    .textarea{
        display:flex;
        justify-content:center;
        flex-direction:column;
    }
    .input-inner{
        display:flex;
        justify-content:center;
        flex-direction:column;
    }

    input[type="text"]:focus{
        outline: 0;
        border:2px solid rgb(0,165,187);
    }
    textarea:focus{
        outline: 0;
        border:2px solid rgb(0,165,187);
        }

        .submit{
            width:150px;
        }
    </style>
</head>
<body class='bg-dark text-white'>
<header class="bg-white navbar-light bg-light" >
<a href="discmark.php" class="navbar-brand ml-4 mt-1"><img src="img/discmark_logo.png" alt="" height="40px;"></a>
    <div class="btn-group">
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fa fa-user-circle"></span><?=$_SESSION['name']?>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <?=$dropdown?>
            <a href="discmark_list.php" class="text-dark"><button class="dropdown-item" type="button">Disc List</button></a>
            <a href="discmark_user_registration.php" class="text-dark"><button class="dropdown-item" type="button">Disc Record</button></a>
            <div class="dropdown-divider"></div>
            <a href="discmark_logout.php"><button class="btn btn-outline-warning dropdown-btn" type="button">Logout</button></a>
        </div>
    </div>
</header>

<main class='mx-auto'>
<div class='main-top'>
    <img src="<?=$row['imageurl']?>" alt="">
    <h1 class='text-center'> Edit your disc content.</h1>
    <a href="<?=$row['videourl']?>"  target='_blank'><img src="<?=$row['videoimage']?>" alt=""></a>
</div>
<form action="discmark_u.php" method="post">
            <div class="form-inner">
                <div class="text-left ">
                    <p class="inputs input-inner">Artist : <input type="text" name="name" value='<?=$row["name"]?>'></p>
                    <p class="inputs input-inner">Album : <input type="text" name="album" value='<?=$row["album"]?>'></p>
                    <p class="inputs input-inner">URL : <input type="text" name="url" value="<?=$row['url']?>"></p>
                </div>
                <div>
                    <p class="inputs input-inner">Tracklist : <textarea name="tracklist" id="text" cols="30" rows="5"><?=$row['tracklist']?></textarea></p>
                </div>
                <div class='text-right'>
                    <p class="inputs textarea text-left">Comment : <textarea name="comment" id="text" cols="30" rows="5"><?=$row["comment"]?>'</textarea></p>
                    <input type="submit" value="Submit" class="submit btn btn-danger mt-4 mr-3">    
                </div>
            </div>
            <a href="discmark_list.php" class="btn-lg btn-info  btn-block mt-5 text-center">Mylist</a>
            <input type="hidden" name="id" value='<?=$row["id"]?>'>
        </form>
</main>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  </body>
</body>
</html>