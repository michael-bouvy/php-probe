<?php
require __DIR__ . "/../vendor/autoload.php";

error_reporting(E_ERROR | E_RECOVERABLE_ERROR);

/* TCP Probe */
$tcpProbe = new PhpProbe\Probe\TcpProbe('Google_DNS', array(), new \PhpProbe\Adapter\NetcatAdapter());
$tcpProbe->host('8.8.8.8')->port(53);

/* HTTPS Probe */
$checkerHttps = new PhpProbe\Check\HttpCheck();
$checkerHttps
    ->addCriterion('httpCode', \PhpProbe\Http\Codes::HTTP_NOT_FOUND)
    ->addCriterion('content', 'G[o]+ggle');

$logger = new \Monolog\Logger('PhpProbe');

$httpsProbe = new PhpProbe\Probe\HttpProbe('Google_HTTPS', array(), new \PhpProbe\Adapter\PhpCurlAdapter());
$httpsProbe
    ->url('https://www.google.com/')
    ->addChecker($checkerHttps)
    ->setLogger($logger);


$manager = new PhpProbe\Manager();
$manager
    ->addProbe($tcpProbe)
    ->addProbe($httpsProbe)
    ->checkAll();

if (php_sapi_name() == 'cli') {
    $manager
        ->output(true)
        ->end();
} else {
    $manager
        ->output(true, true, 'Assets/Templates/output-html.tpl')
        ->end();
}
