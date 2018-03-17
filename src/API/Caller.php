<?php
namespace Clivern\Monkey\API;

use Clivern\Monkey\API\Contract\ResponseInterface;
use Clivern\Monkey\API\Contract\RequestInterface;
use GuzzleHttp\Client;

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
    protected $data;
    protected $status;
    protected $shared;

    /**
     * Class Constructor
     *
     * @param string            $ident    The Caller Ident
     * @param string            $nodeUrl  The CloudStack Node URL
     * @param RequestInterface  $request  The Request Object
     * @param ResponseInterface $response The Response Object
     */
    public function __construct($ident, $nodeUrl, RequestInterface $request, ResponseInterface $response)
    {
        $this->ident = $ident;
        $this->nodeUrl = $nodeUrl;
        $this->request = $request;
        $this->response = $response;
        $this->client = new Client();
    }

    /**
     * Execute The Caller
     *
     * @return Caller
     */
    public function execute()
    {
        $response = $this->client->request($this->request->getMethod(), $this->nodeUrl, [
            'headers' => $this->request->getHeaders(),
            'body' => $this->request->getBody()
        ]);

        $this->response->setRawResponse($response->getBody())
                        ->setResponse(json_decode($response->getBody()))
                        ->setStatusCode($response->getStatusCode());

        call_user_func($this->response->getCallback(), $this);

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
}