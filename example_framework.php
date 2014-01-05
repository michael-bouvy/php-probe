<?php
require __DIR__ . "/vendor/autoload.php";

$webservice = new PhpProbe\Probe\HttpProbe(
    'GitHub_HTTPS',
    array(),
    new \PhpProbe\Adapter\PhpCurlAdapter()
);
$webservice
    ->url('https://api.github.com/repos/michael-bouvy/php-probe')
    ->expectedHttpCode(\PhpProbe\Http\Codes::HTTP_OK);

$webservice->check();

if ($webservice->hasSucceeded()) {
    // Do something
}
