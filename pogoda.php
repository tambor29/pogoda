<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pogoda</title>
</head>
<body>

<h4>Londyn:</h4>
<?php
require_once "vendor/autoload.php";
    
use Buzz\Browser;
use Symfony\Component\Stopwatch\Stopwatch;  
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

    
$fs = new Filesystem();
$stopwatch = new Stopwatch();
$stopwatch->start('weather');

$ftxt = 'cache.txt'; // pobieranie pliku cache
$modTime = filemtime($ftxt); // pobieranie czasu modyfikacji pliku cache
    
$currentTime = time();
        
if ($modTime > $currentTime) {
    $data = file_get_contents('cache.txt'); //pobieranie zawartosci pliku
}else{
    $browser = new Buzz\Browser();
    $response = $browser->get('http://api.openweathermap.org/data/2.5/weather?q=London&APPID=96cdeb166e66f9c035e9e7f8ce665ec8');
    $obj = $response->getContent();
    $fs->dumpFile('cache.txt', $obj);
    $fs->touch('cache.txt', time() + 600);
    $data = $obj;
}
    
    
$obj = json_decode($data);  
    
$temperature = $obj->main->temp;

$event = $stopwatch->stop('weather');
$time = $event->getDuration('weather');
$time = $time/1000;
    
echo "Temperatura: ".$temperature." K<br>";
echo "Czas wyświetlania: ".$time." s<br>"; 
    
?>

</body>
</html>