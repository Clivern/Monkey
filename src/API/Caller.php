<?php
namespace Clivern\Monkey\API;

use Clivern\Monkey\API\Contract\ResponseInterface;
use Clivern\Monkey\API\Contract\RequestInterface;
use Clivern\Monkey\API\CallerStatus;
use GuzzleHttp\Client;
use Clivern\Monkey\API\DumpType;
use Clivern\Monkey\API\Request\RequestType;

/**
 * CloudStack API Caller Class
 *
 * @since 1.0.0
 * @package Clivern\Monkey\API
 */
class Caller {

    protected $client;
    protected $ident;
    protected $nodeData = [
        "nodeUrl"   => "",
        "apiKey"    => "",
        "secretKey" => "",
        "ssoEnabled" => false,
        "ssoKey" => ""
    ];
    protected $response;
    protected $request;

    protected $status;
    protected $shared = [];
    protected $retry = 0;
    protected $retryLimit = 1;
    protected $asyncJob = [];
    protected $asyncJobCommand = "command=queryAsyncJobResult&jobId={jobId}";

    /**
     * Class Constructor
     *
     * @param RequestInterface  $request  The Request Object
     * @param ResponseInterface $response The Response Object
     * @param string            $ident    The Caller Ident
     * @param string            $nodeData  The CloudStack Node URL
     */
    public function __construct(RequestInterface $request = null, ResponseInterface $response = null, $ident = null, $nodeData = [], $retryLimit = 1)
    {
        $this->ident = $ident;
        $this->nodeData = array_merge($this->nodeData, $nodeData);
        $this->request = $request;
        $this->response = $response;
        $this->retryLimit = $retryLimit;
        $this->client = new Client();

        $this->request->addParameter("apiKey", $nodeData["apiKey"]);
        $this->status = CallerStatus::$PENDING;
    }

    /**
     * Execute The Caller
     *
     * @return Caller
     */
    public function execute()
    {
        if (($this->status == CallerStatus::$FINISHED) || ($this->status == CallerStatus::$SUCCEEDED) || ($this->status == CallerStatus::$FAILED)) {
            return $this;
        }

        if ($this->status == CallerStatus::$ASYNC_JOB) {
            return $this->chechAsyncCall();
        }

        if (($this->status == CallerStatus::$PENDING) || ($this->status == CallerStatus::$IN_PROGRESS)) {
            if ($this->request->getType() == RequestType::$ASYNCHRONOUS) {
                return $this->executeAsyncCall();
            } else {
                return $this->executeSyncCall();
            }
        }

        return $this;
    }

    /**
     * Execute Sync Call
     *
     * @return Caller
     */
    protected function executeSyncCall()
    {
        $this->status = CallerStatus::$IN_PROGRESS;
        $this->retry += 1;
        $status = true;

        try {
            $response = $this->client->request($this->request->getMethod(), $this->getUrl(), [
                'headers' => $this->request->getHeaders(),
                'body' => $this->request->getBody(DumpType::$JSON)
            ]);
        } catch (\Exception $e) {

            $status = false;
            $parsedError = !empty((String) $e->getResponse()->getBody(true)) ? json_decode((String) $e->getResponse()->getBody(true), true) : [];
            $errorCode = "M100";
            $errorMessage = "Error! Something Unexpected Happened.";

            foreach ($parsedError as $error_data) {
                $errorCode = (isset($error_data["errorcode"])) ? $error_data["errorcode"] : $errorCode;
                $errorMessage = (isset($error_data["errortext"])) ? $error_data["errortext"] : $errorMessage;
            }

            $this->response->setError([
                "parsed" => $parsedError,
                "plain" => $e->getMessage(),
                "code" => $errorCode,
                "message" => $errorMessage
            ]);
        }

        if ($status) {
            $this->status = ($this->retry >= $this->retryLimit) ? CallerStatus::$SUCCEEDED : CallerStatus::$IN_PROGRESS;
            $this->response->setResponse(json_decode((string) $response->getBody(), true));
        } else {
            $this->status = ($this->retry >= $this->retryLimit) ? CallerStatus::$FAILED : CallerStatus::$IN_PROGRESS;
            $this->response->setResponse([]);
        }

        $callback = $this->response->getCallback();

        if (!empty($callback["method"])) {
            call_user_func_array($callback["method"], [$this, $callback["arguments"]]);
        }

        return $this;
    }

    protected function executeAsyncCall(){}

    protected function chechAsyncCall(){}

    /**
     * Get Request URL
     *
     * @return string
     */
    protected function getUrl()
    {
        $parameters = $this->request->getParameters();

        if ($this->nodeData["ssoEnabled"] && empty($this->nodeData["ssoKey"])) {
            throw new \InvalidArgumentException(
                'Required options not defined: ssoKey'
            );
        }
        ksort($parameters);

        $query = http_build_query($parameters, false, '&', PHP_QUERY_RFC3986);
        $key = $this->nodeData["ssoEnabled"] ? $this->nodeData["ssoKey"] : $this->nodeData["secretKey"];

        $signature = rawurlencode(base64_encode(hash_hmac(
            'SHA1',
            strtolower($query),
            $key,
            true
        )));

        $query = trim($query . '&signature=' . $signature, '?&');

        return $this->nodeData["nodeUrl"] . '?' . $query;
    }

    /**
     * Get Caller Status
     *
     * @return string the caller status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get Request Object
     *
     * @return RequestInterface
     */
    public function getRequestObject()
    {
        return $this->request;
    }

    /**
     * Get Response Object
     *
     * @return ResponseInterface
     */
    public function getResponseObject()
    {
        return $this->response;
    }

    /**
     * Get Request Object
     *
     * @return RequestInterface
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * Get Response Object
     *
     * @return ResponseInterface
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * Get Request Object
     *
     * @return RequestInterface
     */
    public function requestObject()
    {
        return $this->request;
    }

    /**
     * Get Response Object
     *
     * @return ResponseInterface
     */
    public function responseObject()
    {
        return $this->response;
    }

    /**
     * Add Shared Item
     *
     * @param string $key  the shared item key
     * @param mixed $value the shared item value
     * @return Caller
     */
    public function addItem($key, $value)
    {
        $this->shared[$key] = $value;

        return $this;
    }

    /**
     * Get Shared Item
     *
     * @param string $key  the shared item key
     * @return mixed the shared item value
     */
    public function getItem($key)
    {
        return (isset($this->shared[$key])) ? $this->shared[$key] : null;
    }

    /**
     * Check if Item Exists
     *
     * @param string $key  the shared item key
     * @return boolean whether item exists
     */
    public function itemExists($key)
    {
        return (isset($this->shared[$key]));
    }

    /**
     * Dump The Caller Instance Data
     *
     * @param  string $type the type of data
     * @return mixed
     */
    public function dump($type)
    {
        $data = [
            "retry" => $this->retry,
            "shared" => $this->shared,
            "status" => $this->status,
            "ident" => $this->ident,
            "nodeData" => $this->nodeData,
            "asyncJob" => $this->asyncJob,
            "asyncJobCommand" => $this->asyncJobCommand,
            "response" => $this->response->dump(DumpType::$ARRAY),
            "request" => $this->request->dump(DumpType::$ARRAY)
        ];
        return ($type == DumpType::$JSON) ? json_encode($data) : $data;
    }

    /**
     * Reload The Caller Instance Data
     *
     * @param  mixed  $data The Caller Instance Data
     * @param  string $type the type of data
     */
    public function reload($data, $type)
    {
        $data = ($type == DumpType::$JSON) ? json_decode($data, true) : $data;

        $this->retry = $data["retry"];
        $this->shared = $data["shared"];
        $this->status = $data["status"];
        $this->ident = $data["ident"];
        $this->nodeData = $data["nodeData"];
        $this->asyncJob = $data["asyncJob"];
        $this->asyncJobCommand = $data["asyncJobCommand"];
        $this->response->reload($data["response"], DumpType::$ARRAY);
        $this->request->reload($data["request"], DumpType::$ARRAY);
    }
}