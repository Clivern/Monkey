<?php
namespace Clivern\Monkey\API\Response;

use Clivern\Monkey\API\Contract\ResponseInterface;
use Clivern\Monkey\API\DumpType;

/**
 * @since 1.0.0
 * @package Clivern\Monkey\API\Response
 */
class PlainResponse implements ResponseInterface {

    protected $response = [];
    protected $asyncJob = [];
    protected $callback = [
        "method" => null,
        "arguments" => null
    ];
    protected $items = [];
    protected $error = [
        "parsed" => [],
        "plain" => "",
        "code" => "",
        "message" => ""
    ];

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
     * @return array
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
     * Get Async Job As Array
     *
     * @return array The Async Job Data
     */
    public function getAsyncJob()
    {
        return $this->asyncJob;
    }

    /**
     * Set Async Job As Array
     *
     * @param array $asyncJob The Async Job Data
     * @return PlainResponse
     */
    public function setAsyncJob($asyncJob)
    {
        $this->asyncJob = $asyncJob;

        return $this;
    }

    /**
     * Set Error
     *
     * @param array $error The Returned Error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * Get The Error
     *
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get Error As String
     *
     * @return String
     */
    public function getPlainError()
    {
        return $this->error["plain"];
    }

    /**
     * Get Parsed Error
     *
     * @return array
     */
    public function getParsedError()
    {
        return $this->error["parsed"];
    }

    /**
     * Get Error Code
     *
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->error["code"];
    }

    /**
     * Get Error Message
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->error["message"];
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
     * @param string $callbackMethod    the callback method
     * @param array  $callbackArguments the callback arguments
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
     * @return array the response callback
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
            "asyncJob" => $this->asyncJob,
            "callback" => $this->callback,
            "items" => $this->items,
            "error" => $this->error
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
        $this->callback = $data["callback"];
        $this->items = $data["items"];
        $this->asyncJob = $data["asyncJob"];
        $this->error = $data["error"];
    }
}