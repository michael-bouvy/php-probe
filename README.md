PhpProbe [![Build Status](https://travis-ci.org/michael-bouvy/php-probe.png?branch=master)](https://travis-ci.org/michael-bouvy/php-probe)
=========

PhpProbe is a PHP library allowing to simply probe/monitor any applications and services, and either print results or use them in code.

This library (especially because it's _only_ a library) has no

Also, PhpProbe respects PSR-0/1/2 and PSR-3, meaning (among others) it can be used with any [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) compliant logger (see `setLogger()` on `Probe`s) like [Monolog](https://github.com/Seldaek/monolog).

Core concepts
-----------

A `Probe` (eg. Tcp, Http, Database) relies on a (compatible) `Adapter` (eg. Netcat, PhpCurl, PhpMysql), which will return an `AdapterResponse`, possibly containing data to test/check.

At this point a `Probe` is considered successful if it could run successfuly (eq. TCP connection established).

You can also add one or more `Check` to check for specific conditions (eg. response time below a given value, HTTP response code ...).

Usage
-----------

There are 2 ways this library can be used:

####Standalone mode

```php
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
```

See `examples/standalone.php`

Calling `$manager->output(true)` will print results (including success) that will look like this :

```
# Google_DNS - Success
# Google_HTTPS - Failure (Expected value '404' for 'httpCode', got '302' - Expected content 'G[o]+ggle' not found in response.)
```

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

See `examples/framework.php`

***

No matter which way you use this library, you can load the probes from a YAML config file, like this example:

```yaml
probes:
  Google.com_HTTP:
    type: Http
    options:
      url: http://www.google.com
      timeout: 5
    checkers:
      http:
        httpCode: 302
  Google.fr_HTTPS:
    type: Http
    options:
      url: https://www.google.fr
      timeout: 5
    checkers:
      http:
        httpCode: 200
        content: <title>Goorrgle</title>
      generic:
        responseTime: 1
  Google_DNS:
    type: Tcp
    adapter: Netcat
    options:
      host: 8.8.8.8
      port: 53
    checkers:
      generic:
        responseTime: 0.5
  MySQL_Local:
    type: Database
    options:
      host: localhost
      user: root
      password:
    checkers:
      database:
        database: [test, mysql]
```

See `examples/config.yml`

Then simply load the config file in your code:

```php
$manager = new PhpProbe\Manager();
$manager->importConfig('my_config.yml');
$manager->checkAll();
```

See `examples/standalone_config.php`

Available probes, adapters & checkers
-----------

Probes rely on adapters: for instance `TcpProbe` can either work with PHP's `fsockopen()` function, or locally installed Unix utility `netcat`.

####Probes
* `TcpProbe`
 * `FsockopenAdapter` (uses PHP's `fsockopen()` function)
 * `NetcatAdapter` (uses `netcat` utility)
* `HttpProbe`: check for HTTP response code or response content
 * `PhpCurlAdapter` (uses PHP's curl extension)
* `DatabaseProbe`: check for database connection or existing database
 * `PhpMysqlAdapter` (uses PHP's mysql extension)
* `TestProbe`: for testing purposes
 * `TestAdapter`

A `NullAdapter` is also available, always succeeding.

These probes can be used with one or more of the following checkers:

###Checkers and their criterions
* `HttpCheck`:
 * `httpCode`: self-explanatory
 * `content`: check for a given value in the reponse content (also works with regular expressions)
* `DatabaseCheck`:
 * `database`: check for one (or multiple) existing database(s)
* `GenericCheck`:
 * `responseTime`: check if probe's response time is below the given value
* `TestCheck`: for testing purposes

Installation
-----------

####Using Composer

Just require the PhpProbe library in your `composer.json`:

```json
{
    "require": {
        "php-probe/php-probe": "dev-master"
    }
}
```

####From sources

Clone the repository in your project:

```bash
$ git clone https://github.com/michael-bouvy/php-probe
```

You can use the provided autoloader:

```php
require __DIR__ . "/src/PhpProbe/Autoloader.php";
\PhpProbe\Autoloader::register();
```

If you already use a custom (non PSR-0 compliant) autoloader, you might want to prepend PhpProbe's autoloader to autoloaders stack. Simply pass `true` as argument to the `register()` method:

```php
\PhpProbe\Autoloader::register(true);
```

Contributors
-----------

Special thanks to Julien CHICHIGNOUD ([@juchi](https://github.com/juchi/)) for his Checkers concept and implementation.

Testing
-----------

To run the test suite, you need [composer](http://getcomposer.org).

```bash
$ php composer.phar install --dev
$ vendor/bin/phpunit
```

License
-----------

PhpProbe is licensed under the MIT license.