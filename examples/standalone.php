<?php
require __DIR__ . "/../vendor/autoload.php";

error_reporting(E_ERROR);

$tcpProbe = new PhpProbe\Probe\TcpProbe('Google_DNS', array(), new \PhpProbe\Adapter\NetcatAdapter());
$tcpProbe->host('8.8.8.8')->port(53);

$httpProbe = new PhpProbe\Probe\HttpProbe('Google_HTTP', array(), new \PhpProbe\Adapter\PhpCurlAdapter());
$httpProbe->url('http://www.google.com/')->expectedHttpCode(\PhpProbe\Http\Codes::HTTP_FOUND);

$httpsProbe = new PhpProbe\Probe\HttpProbe('Google_HTTPS', array(), new \PhpProbe\Adapter\PhpCurlAdapter());
$httpsProbe->url('https://www.google.com/')
    ->expectedHttpCode(\PhpProbe\Http\Codes::HTTP_FOUND)
    ->contains('G[o]+gle');

$manager = new PhpProbe\Manager();
$manager
    ->addProbe($tcpProbe)
    ->addProbe($httpProbe)
    ->addProbe($httpsProbe)
    ->checkAll();

if (php_sapi_name() == 'cli') {
    $manager
        ->outputText(true)
        ->end();
} else {
    $manager
        ->outputHtml(true)
        ->end();
}
