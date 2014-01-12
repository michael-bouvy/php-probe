<?php
require __DIR__ . "/../vendor/autoload.php";

error_reporting(E_ERROR | E_RECOVERABLE_ERROR);

/* TCP Probe */
$tcpProbe = new PhpProbe\Probe\TcpProbe('Google_DNS', array(), new \PhpProbe\Adapter\NetcatAdapter());
$tcpProbe->host('8.8.8.8')->port(53);

/* HTTP Probe */
$checkerHttp = new PhpProbe\Check\HttpCheck();
$checkerHttp->addCriterion('httpCode', \PhpProbe\Http\Codes::HTTP_FOUND);

$httpProbe = new PhpProbe\Probe\HttpProbe('Google_HTTP', array(), new \PhpProbe\Adapter\PhpCurlAdapter());
$httpProbe
    ->url('http://www.google.com/')
    ->addChecker($checkerHttp);

/* HTTPS Probe */
$checkerHttps = new PhpProbe\Check\HttpCheck();
$checkerHttps
    ->addCriterion('httpCode', \PhpProbe\Http\Codes::HTTP_NOT_FOUND)
    ->addCriterion('content', 'G[o]+gle');

$httpsProbe = new PhpProbe\Probe\HttpProbe('Google_HTTPS', array(), new \PhpProbe\Adapter\PhpCurlAdapter());
$httpsProbe
    ->url('https://www.google.com/')
    ->addChecker($checkerHttps);


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
