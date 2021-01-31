<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8">
<title>Test IMDb</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

<style>
.leftcol{padding:10px; width: 400px}
table {width:598px}
</style>
</head>
<body>

<script>
document.onkeydown=function(e){
	var keyCode = e ? (e.which ? e.which : e.keyCode) : event.keyCode;
	if(keyCode == 13)
	{
		document.test.submit();
	}
}
</script>

<div class="containter-fluid">
<h1 style='text-align:center'>Поиск фильмов в IMDb</h1>
</div>
<div style="display: table; margin: 0 auto">

<form action="site.php" method="get">
<label for="search" >Введите название фильма:</label>
<br>
<input type="text" name="search" id="search">
</form>
</div>
<br>

<?php 

if(isset($_GET['search']))
{
    $search = $_GET['search'];

$rus = "абвгдеёжзиклмнопрстуфхцчшщъыбэюяАБВГДЕЁЖЗИКЛМНОПРСТУФХЦЧШЩЪЫБЭЮЯ";

for ($l = 0; $l < strlen($rus); $l++){
	if (str_contains($search, $rus[$l])){
		echo "<div style='display: table; margin: 0 auto'><h3>Тут можно только по-английски :(</h3></div>";
		exit();
	}
}


$search = trim($search);
$chars = array("#","*","~",'"', "`");
$search = str_replace($chars, "", $search); 
$prefix = $search[0];


$url = 'https://sg.media-imdb.com/suggests/'.$prefix. '/' . $search . '.json';

function get_JSONP($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    
    return $output;
    
}

function jsonp_decode($jsonp, $assoc = false) { 
    if($jsonp[0] !== '[' && $jsonp[0] !== '{') { 
        $jsonp = substr($jsonp, strpos($jsonp, '('));
    }
    $jsonp = trim($jsonp);      
    $jsonp = trim($jsonp,'()'); 
    
    return json_decode($jsonp, $assoc);
    
}


$json = jsonp_decode(get_JSONP($url));

for ($x=0; $x < count($json->d); $x++){
error_reporting(0);
echo "<table border=1 style='margin-left: auto;margin-right: auto'>" . 
"<tr><td class=leftcol><h4>" . $json->d[$x]->l . 
"</h4></td><td rowspan=4> <img src='" . $json->d[$x]->i[0] ."' alt='Нет картинки' width=198 height=292></td></tr>" .
"<tr><td class=leftcol>Актеры: " . $json->d[$x]->s . "</td><td ></td></tr>".
"<tr><td class=leftcol>Год: " . $json->d[$x]->y .  "</td><td ></td></tr>".
"<tr><td class=leftcol>Хронометраж: " . $json->d[0]->v[0]->s . "</td><td ></td></tr>" .
"</table><br>";
}

}

?>

</body>
</html>