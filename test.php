<?php

require_once('lastfm/src/lastfm.api.php');

$caller = new CallerFactory();
$curlCaller = $caller->getCurlCaller();
$curlCaller->setApiKey('8b12b124bc6266b7cc8947fce2501876');

$artistName = "Megadeth";
$limit = 3;
$results = Artist::search($artistName);

var_dump($results);

foreach($results as $item){
    var_dump($item);
	echo '<img src="'.$item -> getImage(0).'">';
	echo $item -> getUrl()."<br>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
</body>
</html>