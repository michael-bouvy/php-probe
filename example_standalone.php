<?php
require __DIR__ . "/vendor/autoload.php";

error_reporting(~ E_ALL);

$tcpProbe = new PhpProbe\Probe\TcpProbe('Google_DNS', array(), new \PhpProbe\Adapter\FsockopenAdapter());
$tcpProbe->host('8.8.8.8')->port(53);

$httpProbe = new PhpProbe\Probe\HttpProbe('Google_HTTP', array(), new \PhpProbe\Adapter\PhpCurlAdapter());
$httpProbe->url('http://www.google.com/')->expectedHttpCode(\PhpProbe\Http\Codes::HTTP_FOUND);

$httpsProbe = new PhpProbe\Probe\HttpProbe('Google_HTTPS', array(), new \PhpProbe\Adapter\PhpCurlAdapter());
$httpsProbe->url('https://www.google.com/')->expectedHttpCode(\PhpProbe\Http\Codes::HTTP_FOUND);

$manager = new PhpProbe\Manager();
$manager
    ->addProbe($tcpProbe)
    ->addProbe($httpProbe)
    ->addProbe($httpsProbe)
    ->checkAll()
    ->outputHtml(true)
    ->end();
