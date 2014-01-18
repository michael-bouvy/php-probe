<?php
require __DIR__ . "/../vendor/autoload.php";

error_reporting(E_ERROR | E_RECOVERABLE_ERROR);

$manager = new PhpProbe\Manager();
$manager->importConfig(__DIR__ . '/config.yml');
$manager->checkAll();

if (php_sapi_name() == 'cli') {
    $manager
        ->output(true)
        ->end();
} else {
    $manager
        ->output(true, true, 'Assets/Templates/output-html.tpl')
        ->end();
}
