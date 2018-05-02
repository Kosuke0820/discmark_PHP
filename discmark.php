<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    .box-cover{
        /* transform:rotate(50deg); */
    }

    .box{
        margin-left:100px;
        height:300px;
        width:100px;
        background-color:rgb(241,0,50);
        transform:rotate3d(40,30,0,70deg);
        /* animation: redbox 2s ease 0s 1 alternate none running; */
    }
    @keyframes redbox {
  0% {
    transform:rotate(180deg);
    height:0px;
  }

  /* 50% {
    height:100px;    
  } */

  100% {
    transform:rotate(180deg);
    height:300px;
    
  }
}
</style>
<body>
    <div class="box-cover">
    <div class="box"></div>
    </div>
</body>
</html>