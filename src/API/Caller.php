<?php
namespace Clivern\Monkey\API;

use Clivern\Monkey\API\Contract\ResponseInterface;
use Clivern\Monkey\API\Contract\RequestInterface;
use Clivern\Monkey\API\CallerStatus;
use GuzzleHttp\Client;
use Clivern\Monkey\API\DumpType;

/**
 * CloudStack API Caller Class
 *
 * @since 1.0.0
 * @package Clivern\Monkey\API
 */
class Caller {

    protected $client;
    protected $ident;
    protected $nodeUrl;
    protected $response;
    protected $request;
    protected $status;
    protected $shared = [];
    protected $retry = 0;

    /**
     * Class Constructor
     *
     * @param RequestInterface  $request  The Request Object
     * @param ResponseInterface $response The Response Object
     * @param string            $ident    The Caller Ident
     * @param string            $nodeUrl  The CloudStack Node URL
     */
    public function __construct(RequestInterface $request = null, ResponseInterface $response = null, $ident = null, $nodeUrl = null)
    {
        $this->ident = $ident;
        $this->nodeUrl = $nodeUrl;
        $this->request = $request;
        $this->response = $response;
        $this->client = new Client();
        $this->status = CallerStatus::$PENDING;
    }

    /**
     * Execute The Caller
     *
     * @return Caller
     */
    public function execute()
    {
        $this->status = CallerStatus::$IN_PROGRESS;
        $this->retry += 1;

        $response = $this->client->request($this->request->getMethod(), $this->nodeUrl, [
            'headers' => $this->request->getHeaders(),
            'body' => $this->request->getBody(DumpType::$JSON)
        ]);


        $this->response->setResponse(json_decode($response->getBody(), true))
                        ->setStatusCode($response->getStatusCode());

        $callback = $this->response->getCallback();
        call_user_func_array($callback["method"], [$this, $callback["arguments"]]);

        return $this;
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
            "nodeUrl" => $this->nodeUrl,
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
        $this->nodeUrl = $data["nodeUrl"];
        $this->response->reload($data["response"], DumpType::$ARRAY);
        $this->request->reload($data["request"], DumpType::$ARRAY);
    }
}