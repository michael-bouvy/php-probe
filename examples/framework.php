<?php
require __DIR__ . "/../vendor/autoload.php";

$webservice = new PhpProbe\Probe\HttpProbe(
    'GitHub_HTTPS',
    array(),
    new \PhpProbe\Adapter\PhpCurlAdapter()
);

$webservice->url('https://api.github.com/repos/michael-bouvy/php-probe');

$httpChecker = new \PhpProbe\Check\HttpCheck();
$httpChecker->addCriterion('httpCode', \PhpProbe\Http\Codes::HTTP_BAD_GATEWAY);
$webservice->addChecker($httpChecker);

$webservice->check();

if ($webservice->hasSucceeded()) {
    // Do something cool
}
