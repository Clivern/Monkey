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

### Running Sync Calls

In order to run sync calls, Like creating a new user, [you need to check cloudstack api to get the command and the required parameters](http://cloudstack.apache.org/api/apidocs-4.11/apis/createUser.html). First lets create a call that will fail due to missing parametes and see the response data:

```php
include_once dirname(__FILE__) . '/vendor/autoload.php';

use Clivern\Monkey\Util\Config;
use Clivern\Monkey\API\Request\PlainRequest;
use Clivern\Monkey\API\Response\PlainResponse;
use Clivern\Monkey\API\Request\RequestMethod;
use Clivern\Monkey\API\Request\RequestType;
use Clivern\Monkey\API\Caller;


// Create a cloudStack credentials config
$config = new Config();
$config->addCloudStackServer("us_dc_clsk_01", [
    "api_url"   => "http://clsk_url.com:8080/client/api",
    "api_key"    => "api_key_here",
    "secret_key" => "secret_key_here"
]);

// Create request object with a missing parameter account :(
$request = new PlainRequest();
$request->setMethod(RequestMethod::$GET)
        ->setType(RequestType::$SYNCHRONOUS)
        ->addParameter("command", "createUser")
        ->addParameter("email", "hello@clivern.com")
        ->addParameter("firstname", "John")
        ->addParameter("lastname", "Doe")
        ->addParameter("password", "clivern")
        ->addParameter("username", "clivern");

// Create response object without callbacks
$response = new PlainResponse();

// Create a caller object with the request and response and ident create_account
$caller = new Caller($request, $response, "create_account", $config->getCloudStackServer("us_dc_clsk_01"));

// Run the call
$caller->execute();

// Debug the caller status and response data
var_dump($caller->getStatus()); // Return string(6) "FAILED"
var_dump($caller->response()->getResponse()); // Returns array(0) { }
var_dump($caller->response()->getErrorCode()); // Returns int(431)
var_dump($caller->response()->getErrorMessage()); // Returns string(73) "Unable to execute API command createuser due to missing parameter account"
```

Now let's create a successful call:

```php
include_once dirname(__FILE__) . '/vendor/autoload.php';

use Clivern\Monkey\Util\Config;
use Clivern\Monkey\API\Request\PlainRequest;
use Clivern\Monkey\API\Response\PlainResponse;
use Clivern\Monkey\API\Request\RequestMethod;
use Clivern\Monkey\API\Request\RequestType;
use Clivern\Monkey\API\Caller;


// Create a cloudStack credentials config
$config = new Config();
$config->addCloudStackServer("us_dc_clsk_01", [
    "api_url"   => "http://clsk_url.com:8080/client/api",
    "api_key"    => "api_key_here",
    "secret_key" => "secret_key_here"
]);

// Create request object
$request = new PlainRequest();
$request->setMethod(RequestMethod::$GET)
        ->setType(RequestType::$SYNCHRONOUS)
        ->addParameter("command", "createUser")
        ->addParameter("account", "admin")
        ->addParameter("email", "hello@clivern.com")
        ->addParameter("firstname", "John")
        ->addParameter("lastname", "Doe")
        ->addParameter("password", "clivern")
        ->addParameter("username", "clivern");

// Create response object without callbacks
$response = new PlainResponse();

// Create a caller object with the request and response and ident create_account
$caller = new Caller($request, $response, "create_account", $config->getCloudStackServer("us_dc_clsk_01"));

// Run the call
$caller->execute();

// Debug the caller status and response data
var_dump($caller->getStatus()); // Return string(9) "SUCCEEDED"
var_dump($caller->response()->getResponse()); // Returns array(1) { ["createuserresponse"]=> array(1) { ["user"]=> array(18) { ["id"]=> string(36) "6980f41b-73e5-4848-ad90-7859efb613ad" .....}}}
var_dump($caller->response()->getErrorCode()); // Returns string(0) ""
var_dump($caller->response()->getErrorMessage()); // Returns string(0) ""
```

### Running Async Jobs



### Running Complex Jobs




### Building The API Request



### Building The API Response



### Creating a Caller



### Create and Call The Job



### More Complex Usage


Misc
====

Changelog
---------
Version 1.0.0:
```
Initial Release.
```

Acknowledgements
----------------

© 2018, Clivern. Released under [MIT License](https://opensource.org/licenses/mit-license.php).

**Monkey** is authored and maintained by [@clivern](http://github.com/clivern).