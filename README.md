Monkey
======

Apache CloudStack SDK in PHP.

*Current Version: Under Development*

[![Build Status](https://travis-ci.org/Clivern/Monkey.svg?branch=master)](https://travis-ci.org/Clivern/Monkey)

Installation
------------

To install the package via `composer`, use the following:

```php
composer require clivern/monkey
```

This command requires you to have Composer installed globally.


Usage
-----

After adding the package as a dependency, Please read the following steps:

### Configure CloudStack Credentials

```php
include_once dirname(__FILE__) . '/vendor/autoload.php';

use Clivern\Monkey\Util\Config;

$config = new Config();
$config->addCloudStackServer("us_dc_clsk_01", [
	"api_url"   => "http://clsk_url.com:8080/client/api",
    "api_key"    => "api_key_here",
    "secret_key" => "secret_key_here"
]);

// OR

$config = new Config([
	"us_dc_clsk_01" => [
		"api_url"   => "http://clsk_url.com:8080/client/api",
	    "api_key"    => "api_key_here",
	    "secret_key" => "secret_key_here"
	]
]);

// To Check if CloudStack Server Credentials Exists
$config->isCloudStackServerExists("us_dc_clsk_01"); // Return Boolean

// To Get CloudStack Server Credentials
$config->getCloudStackServer("us_dc_clsk_01"); // Return array & May be empty if not exist

// To Get All CloudStack Servers Credentials
$config->getCloudStackServers(); // Return Array

// To Remove CloudStack Server
$config->removeCloudStackServer("us_dc_clsk_01"); // Return Boolean
```


Misc
====

Changelog
---------
Version 1.0.0:
```
Stay Tuned ;)
```

Acknowledgements
----------------

© 2018, Clivern. Released under [MIT License](https://opensource.org/licenses/mit-license.php).

**Monkey** is authored and maintained by [@clivern](http://github.com/clivern).