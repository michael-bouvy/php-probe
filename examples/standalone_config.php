<?php
require __DIR__ . "/../vendor/autoload.php";

error_reporting(~E_ALL);

$manager = new PhpProbe\Manager();
$manager->importConfig(__DIR__ . '/config.yml');
$manager->checkAll();

if (php_sapi_name() == 'cli') {
    $manager
        ->outputText(true)
        ->end();
} else {
    $manager
        ->outputHtml(true)
        ->end();
}
