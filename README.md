PhpProbe [![Build Status](https://travis-ci.org/michael-bouvy/php-probe.png?branch=master)](https://travis-ci.org/michael-bouvy/php-probe)
=========

PhpProbe is a PHP library allowing to simply probe/monitor any applications and services, and either print results or use them in code.

Installation
-----------

####Using Composer

Just require the PhpProbe library in your `composer.json` : 

```json
{
    "require": {
        "php-probe/php-probe": "dev-master"
    }
}
```

####From sources

Clone the repository in your project :

```bash
     $ git clone https://github.com/michael-bouvy/php-probe
```

You can use the provided autoloader :

```php
require __DIR__ . "/src/PhpProbe/Autoloader.php";
\PhpProbe\Autoloader::register();
```

If you already use a custom (non PSR-0 compliant) autoloader, you might want to prepend PhpProbe's autoloader to autoloaders stack. Simply pass `true` as argument to the `register()` method :

```php
\PhpProbe\Autoloader::register(true);
```

Usage
-----------

There are 2 ways this library can be used : 

####Standalone mode

```php
<?php
require __DIR__ . "/vendor/autoload.php";

$tcpProbe = new PhpProbe\Probe\TcpProbe('Google_DNS', array(), new \PhpProbe\Adapter\Fsockopen());
$tcpProbe->host('8.8.8.8')->port(53);

$httpProbe = new PhpProbe\Probe\HttpProbe('Google_HTTP', array(), new \PhpProbe\Adapter\PhpCurl());
$httpProbe->url('http://www.google.com/')->expectedHttpCode(\PhpProbe\Http\Codes::HTTP_FOUND);

$httpsProbe = new PhpProbe\Probe\HttpProbe('Google_HTTPS', array(), new \PhpProbe\Adapter\PhpCurl());
$httpsProbe->url('https://www.google.com/')->expectedHttpCode(\PhpProbe\Http\Codes::HTTP_FOUND);

$manager = new PhpProbe\Manager();
$manager
    ->addProbe($tcpProbe)
    ->addProbe($httpProbe)
    ->addProbe($httpsProbe)
    ->checkAll()
    ->outputHtml(true)
    ->end();
```

See `example_standalone.php`

####Inside a framework/tool

```php
$webservice = new PhpProbe\Probe\HttpProbe(
    'GitHub_HTTPS',
    array(),
    new \PhpProbe\Adapter\PhpCurl()
);
$webservice
    ->url('https://api.github.com/repos/michael-bouvy/php-probe')
    ->expectedHttpCode(\PhpProbe\Http\Codes::HTTP_OK);

$webservice->check();

if ($webservice->hasSucceeded()) {
    // Do something
}
```

See `example_framework.php`

***

No matter which way you use this library, you can load the probes from a YAML config file, like this example :

```yaml
probes:
  Google.com_HTTP:
    type: Http
    options:
      url: http://www.google.com
      expectedHttpCode: 302
      timeout: 5
  Google.fr_HTTPS:
    type: Http
    options:
      url: https://www.google.fr
      expectedHttpCode: 200
      timeout: 5
  Google_DNS:
    type: Tcp
    options:
      host: 8.8.8.8
      port: 53
```

See `config_sample.yml`

Then simply load the config file in your code :

```php
$manager = new PhpProbe\Manager();
$manager->importConfig('config_sample.yml');
$manager->checkAll();
```

See `example_standalone_config.php`

Available probes & adapters
-----------

Probes rely on adapters : for instance `TcpProbe` can either work with PHP's `fsockopen()` function, or locally installed Unix utility `netcat`.

####Probes
* `TcpProbe`
 * `FsockopenAdapter` (uses PHP's `fsockopen()` function)
 * `NetcatAdapter` (uses `netcat` utility)
* `HttpProbe`
 * `PhpCurlAdapter` (uses PHP's curl extension)

Testing
-----------

To run the test suite, you need [composer](http://getcomposer.org).
 
     $ php composer.phar install --dev
     $ vendor/bin/phpunit

License
-----------

PhpProbe is licensed under the MIT license.