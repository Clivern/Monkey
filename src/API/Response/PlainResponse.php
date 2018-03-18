<?php
namespace Clivern\Monkey\API\Response;

use Clivern\Monkey\API\Contract\ResponseInterface;
use Clivern\Monkey\API\DumpType;

/**
 * @since 1.0.0
 * @package Clivern\Monkey\API\Response
 */
class PlainResponse implements ResponseInterface {

    protected $response;
    protected $statusCode;
    protected $callback;
    protected $items;

    /**
     * Class Constructor
     *
     * @param mixed $callback The response callback
     */
    public function __construct($callbackMethod = null, $callbackArguments = [])
    {
        $this->callback = [
            "method" => $callbackMethod,
            "arguments" => $callbackArguments
        ];
    }

    /**
     * Get Response As Array
     *
     * @return PlainResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set Response As Array
     *
     * @param array $response The Response
     * @return PlainResponse
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Set Status Code
     *
     * @param integer $statusCode the response status code
     * @return PlainResponse
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get Status Code
     *
     * @return Integer The Status Code
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Add Response Item
     *
     * @param string $key  the response item key
     * @param mixed $value the response item value
     * @return PlainResponse
     */
    public function addItem($key, $value)
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * Get Response Item
     *
     * @param string $key  the response item key
     * @return mixed the response item value
     */
    public function getItem($key)
    {
        return (isset($this->items[$key])) ? $this->items[$key] : null;
    }

    /**
     * Set Callback
     *
     * @param mixed $callback the response callback
     * @return PlainResponse
     */
    public function setCallback($callbackMethod = null, $callbackArguments = [])
    {
        $this->callback = [
            "method" => $callbackMethod,
            "arguments" => $callbackArguments
        ];

        return $this;
    }

    /**
     * Get Callback
     *
     * @return mixed the response callback
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Dump The PlainResponse Instance Data
     *
     * @param  string $type the type of data
     * @return mixed
     */
    public function dump($type)
    {
        $data = [
            "response" => $this->response,
            "statusCode" => $this->statusCode,
            "callback" => $this->callback,
            "items" => $this->items
        ];
        return ($type == DumpType::$JSON) ? json_encode($data) : $data;
    }

    /**
     * Reload The PlainResponse Instance Data
     *
     * @param  mixed  $data The PlainResponse Instance Data
     * @param  string $type the type of data
     */
    public function reload($data, $type)
    {
        $data = ($type == DumpType::$JSON) ? json_decode($data, true) : $data;
        $this->response = $data["response"];
        $this->statusCode = $data["statusCode"];
        $this->callback = $data["callback"];
        $this->items = $data["items"];
    }
}