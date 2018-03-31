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

In case of async calls, we need to use another class called `Job` to execute our caller(s). The `Job` class can be exported as json encoded string and stored in database and reloaded again from the last state. This means that we can build a job that hold a lot of sync and async calls and the job class will continue every time we reload it and complete the main request.

Also `Job` class can retry to run your caller(s) if it failed and once it succeeded, it will move to the next caller. and for sure you will provide the number of trials for each job or per each caller.

First let's [create a job that will stop a virtual machine](http://cloudstack.apache.org/api/apidocs-4.11/apis/stopVirtualMachine.html). This job needs to run at least two times, one to create the machine and another to check the job status. Since we don't use database to store job state, we will do this manually. But for sure in real world we will store job in database and run in background.

```php
include_once dirname(__FILE__) . '/vendor/autoload.php';

use Clivern\Monkey\Util\Config;
use Clivern\Monkey\API\Request\PlainRequest;
use Clivern\Monkey\API\Response\PlainResponse;
use Clivern\Monkey\API\Request\RequestMethod;
use Clivern\Monkey\API\Request\RequestType;
use Clivern\Monkey\API\Caller;
use Clivern\Monkey\API\Job;
use Clivern\Monkey\API\DumpType;
use Clivern\Monkey\API\Factory;
use Clivern\Monkey\API\JobStatus;


$config = new Config();
$config->addCloudStackServer("us_dc_clsk_01", [
    "api_url"   => "http://clsk_url.com:8080/client/api",
    "api_key"    => "api_key_here",
    "secret_key" => "secret_key_here"
]);

$request = new PlainRequest();
$request->setMethod(RequestMethod::$GET)
        ->setType(RequestType::$ASYNCHRONOUS)
        ->addParameter("command", "stopVirtualMachine")
        ->addParameter("id", "4c9c8759-de26-41bb-9a22-fe51b9f0c9af");

$response = new PlainResponse();

$caller = new Caller($request, $response, "stop_virtual_machine", $config->getCloudStackServer("us_dc_clsk_01"));


// Create a job with one caller and 4 default trials in case of failure
$job = new \Clivern\Monkey\API\Job([
    $caller
], 4);

// Job initial state to store in database
$initialJobState = $job->dump(DumpType::$JSON);
var_dump($initialJobState);

$currentJobState = $initialJobState;
$finished = false;
$currentJob = null;

while (!$finished) {
    $currentJob = Factory::job()->reload($currentJobState, DumpType::$JSON);
    $currentJob->execute();
    $finished = (($currentJob->getStatus() == JobStatus::$FAILED) || ($currentJob->getStatus() == JobStatus::$SUCCEEDED)) ? true : false;
    $currentJobState = $currentJob->dump(DumpType::$JSON);
    sleep(5);
}

if( $currentJob != null ){
    var_dump($currentJob->getStatus());
    var_dump($currentJob->getCaller("stop_virtual_machine")->getStatus());
    var_dump($currentJob->getCaller("stop_virtual_machine")->response()->getResponse());
    var_dump($currentJob->getCaller("stop_virtual_machine")->response()->getErrorCode());
    var_dump($currentJob->getCaller("stop_virtual_machine")->response()->getErrorMessage());
    var_dump($currentJob->dump(DumpType::$JSON));
}
```

Also [we can start the virtual machine again](http://cloudstack.apache.org/api/apidocs-4.11/apis/startVirtualMachine.html)

```php
include_once dirname(__FILE__) . '/vendor/autoload.php';

use Clivern\Monkey\Util\Config;
use Clivern\Monkey\API\Request\PlainRequest;
use Clivern\Monkey\API\Response\PlainResponse;
use Clivern\Monkey\API\Request\RequestMethod;
use Clivern\Monkey\API\Request\RequestType;
use Clivern\Monkey\API\Caller;
use Clivern\Monkey\API\Job;
use Clivern\Monkey\API\DumpType;
use Clivern\Monkey\API\Factory;
use Clivern\Monkey\API\JobStatus;


$config = new Config();
$config->addCloudStackServer("us_dc_clsk_01", [
    "api_url"   => "http://clsk_url.com:8080/client/api",
    "api_key"    => "api_key_here",
    "secret_key" => "secret_key_here"
]);

$request = new PlainRequest();
$request->setMethod(RequestMethod::$GET)
        ->setType(RequestType::$ASYNCHRONOUS)
        ->addParameter("command", "startVirtualMachine")
        ->addParameter("id", "4c9c8759-de26-41bb-9a22-fe51b9f0c9af");

$response = new PlainResponse();

$caller = new Caller($request, $response, "start_virtual_machine", $config->getCloudStackServer("us_dc_clsk_01"));


// Create a job with one caller and 4 default trials in case of failure
$job = new \Clivern\Monkey\API\Job([
    $caller
], 4);

// Job initial state to store in database
$initialJobState = $job->dump(DumpType::$JSON);
var_dump($initialJobState);

$currentJobState = $initialJobState;
$finished = false;
$currentJob = null;

while (!$finished) {
    $currentJob = Factory::job()->reload($currentJobState, DumpType::$JSON);
    $currentJob->execute();
    $finished = (($currentJob->getStatus() == JobStatus::$FAILED) || ($currentJob->getStatus() == JobStatus::$SUCCEEDED)) ? true : false;
    $currentJobState = $currentJob->dump(DumpType::$JSON);
    sleep(5);
}

if( $currentJob != null ){
    var_dump($currentJob->getStatus());
    var_dump($currentJob->getCaller("start_virtual_machine")->getStatus());
    var_dump($currentJob->getCaller("start_virtual_machine")->response()->getResponse());
    var_dump($currentJob->getCaller("start_virtual_machine")->response()->getErrorCode());
    var_dump($currentJob->getCaller("start_virtual_machine")->response()->getErrorMessage());
    var_dump($currentJob->dump(DumpType::$JSON));
}
```

### Running Complex Jobs


### More Complex Usage


### Monkey on Production


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