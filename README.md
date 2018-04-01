Monkey
======

Apache CloudStack SDK in PHP.

[![Build Status](https://travis-ci.org/Clivern/Monkey.svg?branch=master)](https://travis-ci.org/Clivern/Monkey)
[![License](https://poser.pugx.org/clivern/monkey/license.svg)](https://packagist.org/packages/clivern/monkey)
[![Latest Stable Version](https://poser.pugx.org/clivern/monkey/v/stable.svg)](https://packagist.org/packages/clivern/monkey)

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

If we want to run two calls but they are independent on each other, it is all about the order. We just need to run one then another like stop and start the virtual machine. In this case we will create two callers and create a job with the two callers like the following:

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

$request1 = new PlainRequest();
$request1->setMethod(RequestMethod::$GET)
        ->setType(RequestType::$ASYNCHRONOUS)
        ->addParameter("command", "stopVirtualMachine")
        ->addParameter("id", "4c9c8759-de26-41bb-9a22-fe51b9f0c9af");

$response1 = new PlainResponse();

$caller1 = new Caller($request1, $response1, "stop_virtual_machine", $config->getCloudStackServer("us_dc_clsk_01"));


$request2 = new PlainRequest();
$request2->setMethod(RequestMethod::$GET)
        ->setType(RequestType::$ASYNCHRONOUS)
        ->addParameter("command", "startVirtualMachine")
        ->addParameter("id", "4c9c8759-de26-41bb-9a22-fe51b9f0c9af");

$response2 = new PlainResponse();

$caller2 = new Caller($request2, $response2, "start_virtual_machine", $config->getCloudStackServer("us_dc_clsk_01"));


// Create a job with two callers and 4 default trials in case of failure
$job = new \Clivern\Monkey\API\Job([
    $caller1,
    $caller2
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
    var_dump($currentJob->getCaller("start_virtual_machine")->getStatus());
    var_dump($currentJob->getCaller("start_virtual_machine")->response()->getResponse());
    var_dump($currentJob->getCaller("start_virtual_machine")->response()->getErrorCode());
    var_dump($currentJob->getCaller("start_virtual_machine")->response()->getErrorMessage());
    var_dump($currentJob->dump(DumpType::$JSON));
}
```

### More Complex Usage

Let's make it more complex, Now we need to deploy a virtual server with these data:

- Template `CentOS 5.6 (64-bit) no GUI (Simulator)`
- Service Offering: `Small Instance`
- Zone: `Sandbox-simulator`

And as you know [from API we need the id of the template, service offering and zone](http://cloudstack.apache.org/api/apidocs-4.11/apis/deployVirtualMachine.html). In this case we will create a separate callers to get these ids before we deploy the virtual server. and we will use response callbacks to store these ids in the caller shared data to be used by the job object.


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
use Clivern\Monkey\API\CallerStatus;


class TemplatesFilter
{
    public static function addTemplateId($caller, $arguments)
    {
        if ($caller->getStatus() !=  CallerStatus::$SUCCEEDED) {
            return false;
        }
        $response = $caller->response()->getResponse();
        if( !is_array($response) || !isset($response["listtemplatesresponse"]) || !isset($response["listtemplatesresponse"]["template"]) ){
            return false;
        }
        foreach ($response["listtemplatesresponse"]["template"] as $template) {
            if(isset($arguments['template_name']) && ($template['name'] == $arguments['template_name'])) {
                $caller->addItem("templateid", $template['id']);
                break;
            }elseif(!isset($arguments['template_name'])){
                $caller->addItem("templateid", $template['id']);
                break;
            }
        }

        if( empty($caller->getItem("templateid")) ){
            $caller->setStatus(CallerStatus::$FAILED);
            $caller->response()->setErrorCode("M200");
            $caller->response()->setErrorMessage((isset($arguments['template_name']))
                ? sprintf("Error! Can't find template with name: %s", $arguments['template_name'])
                : "Error! Can't find any template."
            );
        }
    }
}

class ServiceOfferingsFilter
{
    public static function addServiceOfferId($caller, $arguments)
    {
        if ($caller->getStatus() !=  CallerStatus::$SUCCEEDED) {
            return false;
        }
        $response = $caller->response()->getResponse();
        if( !is_array($response) || !isset($response["listserviceofferingsresponse"]) || !isset($response["listserviceofferingsresponse"]["serviceoffering"]) ){
            return false;
        }
        foreach ($response["listserviceofferingsresponse"]["serviceoffering"] as $serviceoffering) {
            if(isset($arguments['serviceoffering_name']) && ($serviceoffering['name'] == $arguments['serviceoffering_name'])) {
                $caller->addItem("serviceofferingid", $serviceoffering['id']);
                break;
            }elseif(!isset($arguments['serviceoffering_name'])){
                $caller->addItem("serviceofferingid", $serviceoffering['id']);
                break;
            }
        }
        if( empty($caller->getItem("serviceofferingid")) ){
            $caller->setStatus(CallerStatus::$FAILED);
            $caller->response()->setErrorCode("M200");
            $caller->response()->setErrorMessage((isset($arguments['serviceoffering_name']))
                ? sprintf("Error! Can't find service offering with name: %s", $arguments['serviceoffering_name'])
                : "Error! Can't find any service offering."
            );
        }
    }
}

class ZoneFilter
{
    public static function addZoneId($caller, $arguments)
    {
        if ($caller->getStatus() !=  CallerStatus::$SUCCEEDED) {
            return false;
        }
        $response = $caller->response()->getResponse();
        if( !is_array($response) || !isset($response["listzonesresponse"]) || !isset($response["listzonesresponse"]["zone"]) ){
            return false;
        }
        foreach ($response["listzonesresponse"]["zone"] as $zone) {
            if(isset($arguments['zone_name']) && ($zone['name'] == $arguments['zone_name'])) {
                $caller->addItem("zoneid", $zone['id']);
                break;
            }elseif(!isset($arguments['zone_name'])){
                $caller->addItem("zoneid", $zone['id']);
                break;
            }
        }
        if( empty($caller->getItem("zoneid")) ){
            $caller->setStatus(CallerStatus::$FAILED);
            $caller->response()->setErrorCode("M200");
            $caller->response()->setErrorMessage((isset($arguments['zone_name']))
                ? sprintf("Error! Can't find zone with name: %s", $arguments['zone_name'])
                : "Error! Can't find any zone."
            );
        }
    }
}

$config = new Config();
$config->addCloudStackServer("us_dc_clsk_01", [
    "api_url"   => "http://159.89.45.203:8080/client/api",
    "api_key"    => "RSB9O6bwGi64gjd0Xy4ow14LD3TNyyv6oX6WdBHtukJx5-MpLuXYYhQjj5x23nU2CyD_XQhdOa00NDDLRxVaSA",
    "secret_key" => "Auqc9pS9G-QjmQVOoU7nvlSwIG_OtkLWD4iqp0lnS7H_pZpLs6M8iP8T9C4BMFEd4g4R8c2GCmBYkRFQbd5Fxw",
]);

$request1 = new PlainRequest();
$request1->setMethod(RequestMethod::$GET)
        ->setType(RequestType::$SYNCHRONOUS)
        ->addParameter("command", "listTemplates")
        ->addParameter("templatefilter", "featured");

$response1 = new PlainResponse("\TemplatesFilter::addTemplateId", ["template_name" => "CentOS 5.6 (64-bit) no GUI (Simulator)"]);

$caller1 = new Caller($request1, $response1, "list_templates", $config->getCloudStackServer("us_dc_clsk_01"));


$request2 = new PlainRequest();
$request2->setMethod(RequestMethod::$GET)
        ->setType(RequestType::$SYNCHRONOUS)
        ->addParameter("command", "listServiceOfferings");

$response2 = new PlainResponse("\ServiceOfferingsFilter::addServiceOfferId", ["serviceoffering_name" => "Small Instance"]);

$caller2 = new Caller($request2, $response2, "list_service_offering", $config->getCloudStackServer("us_dc_clsk_01"));


$request3 = new PlainRequest();
$request3->setMethod(RequestMethod::$GET)
        ->setType(RequestType::$SYNCHRONOUS)
        ->addParameter("command", "listZones");

$response3 = new PlainResponse("\ZoneFilter::addZoneId", ["zone_name" => "Sandbox-simulator"]);

$caller3 = new Caller($request3, $response3, "list_zone", $config->getCloudStackServer("us_dc_clsk_01"));


$request4 = new PlainRequest();
$request4->setMethod(RequestMethod::$GET)
        ->setType(RequestType::$ASYNCHRONOUS)
        ->addParameter("command", "deployVirtualMachine")
        ->addParameter("serviceofferingid", "@list_service_offering->serviceofferingid")
        ->addParameter("templateid", "@list_templates->templateid")
        ->addParameter("zoneid", "@list_zone->zoneid");

$response4 = new PlainResponse();

$caller4 = new Caller($request4, $response4, "deploy_virtual_machine", $config->getCloudStackServer("us_dc_clsk_01"));


// Create a job with four callers and 4 default trials in case of failure
$job = new \Clivern\Monkey\API\Job([
    $caller1,
    $caller2,
    $caller3,
    $caller4
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

    var_dump($currentJob->getCaller("list_service_offering")->getStatus());
    var_dump($currentJob->getCaller("list_service_offering")->response()->getResponse());
    var_dump($currentJob->getCaller("list_service_offering")->response()->getErrorCode());
    var_dump($currentJob->getCaller("list_service_offering")->response()->getErrorMessage());

    var_dump($currentJob->getCaller("list_templates")->getStatus());
    var_dump($currentJob->getCaller("list_templates")->response()->getResponse());
    var_dump($currentJob->getCaller("list_templates")->response()->getErrorCode());
    var_dump($currentJob->getCaller("list_templates")->response()->getErrorMessage());

    var_dump($currentJob->getCaller("list_zone")->getStatus());
    var_dump($currentJob->getCaller("list_zone")->response()->getResponse());
    var_dump($currentJob->getCaller("list_zone")->response()->getErrorCode());
    var_dump($currentJob->getCaller("list_zone")->response()->getErrorMessage());

    var_dump($currentJob->getCaller("deploy_virtual_machine")->getStatus());
    var_dump($currentJob->getCaller("deploy_virtual_machine")->response()->getResponse());
    var_dump($currentJob->getCaller("deploy_virtual_machine")->response()->getErrorCode());
    var_dump($currentJob->getCaller("deploy_virtual_machine")->response()->getErrorMessage());
    var_dump($currentJob->dump(DumpType::$JSON));
}
```

### Monkey on Production

Here I was trying to describe different usage cases of monkey but in case of production, we must have a database table(s) for our jobs and executer to run our jobs in background.

We will need custom request classes for each specific command so we don't need to provide command data every time we create a request.

```php
use Clivern\Monkey\API\Contract\RequestInterface;
use Clivern\Monkey\API\Request\PlainRequest;


class CreateUser extends PlainRequest implements RequestInterface {

}
```

We will need custom response classes for each specific command so we don't need to parse the response to fetch the useful response data and has a direct method to do that.

```php
use Clivern\Monkey\API\Contract\ResponseInterface;
use Clivern\Monkey\API\Response\PlainResponse;


class CreateUser extends PlainResponse implements ResponseInterface {

}
```

Also build our response callbacks for calls so when we use it, we will be sure that these data will be available for other callers within the job. Finally i created a [repository to share these ideas and how to apply them in symfony framework but for sure it can be applied to other frameworks](https://github.com/Clivern/Koba). I named it [Koba](https://github.com/Clivern/Koba) like the Monkey from [Dawn of the Planet of the Apes](https://nl.wikipedia.org/wiki/Dawn_of_the_Planet_of_the_Apes) :grin:


Misc
====

Changelog
---------
Version 1.0.1:
```
Add More Test Cases.
```

Version 1.0.0:
```
Initial Release.
```

Acknowledgements
----------------

© 2018, Clivern. Released under [MIT License](https://opensource.org/licenses/mit-license.php).

**Monkey** is authored and maintained by [@clivern](http://github.com/clivern).