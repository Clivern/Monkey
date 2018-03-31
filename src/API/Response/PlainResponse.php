<?php

namespace Clivern\Monkey\API\Response;

use Clivern\Monkey\API\DumpType;
use Clivern\Monkey\API\Contract\ResponseInterface;


/**
 * Plain Response Class
 *
 * @since 1.0.0
 * @package Clivern\Monkey\API\Response
 */
class PlainResponse implements ResponseInterface {

    protected $response = [];
    protected $asyncJob = [];
    protected $asyncJobId = "";
    protected $items = [];
    protected $callback = [
        "method" => null,
        "arguments" => null
    ];
    protected $error = [
        "parsed" => [],
        "plain" => "",
        "code" => "",
        "message" => ""
    ];


    /**
     * Class Constructor
     *
     * @param string $callbackMethod The response callback class and method
     * @param array  $callbackArguments The response callback arguments
     */
    public function __construct($callbackMethod = null, $callbackArguments = [])
    {
        $this->callback = [
            "method" => $callbackMethod,
            "arguments" => $callbackArguments
        ];
    }

    /**
     * Set Response
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
     * Set Async Job
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
     * Set Async Job Id
     *
     * @param string $asyncJobId
     * @return PlainResponse
     */
    public function setAsyncJobId($asyncJobId)
    {
        $this->asyncJobId = $asyncJobId;

        return $this;
    }

    /**
     * Set Error
     *
     * @param array $error the returned error
     * @return PlainResponse
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
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
     * Check if Item Exists
     *
     * @param   string $key the response item key
     * @return  boolean Whether item exists or not
     */
    public function itemExists($key)
    {
        return (isset($this->items[$key]));
    }

    /**
     * Get Response
     *
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get Async Job
     *
     * @return array The Async Job Data
     */
    public function getAsyncJob()
    {
        return $this->asyncJob;
    }

    /**
     * Get Async Job Id
     *
     * @return string
     */
    public function getAsyncJobId()
    {
        return $this->asyncJobId;
    }

    /**
     * Get Response Item
     *
     * @param string $key  the response item key
     * @return mixed the response item value
     */
    public function getItem($key)
    {
        return ($this->itemExists($key)) ? $this->items[$key] : null;
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
     * Get The Error
     *
     * @return array the returned error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get Plain Error
     *
     * @return string the plain error
     */
    public function getPlainError()
    {
        return $this->error["plain"];
    }

    /**
     * Get Parsed Error
     *
     * @return array the parsed error
     */
    public function getParsedError()
    {
        return $this->error["parsed"];
    }

    /**
     * Get Error Code
     *
     * @return integer the error code
     */
    public function getErrorCode()
    {
        return $this->error["code"];
    }

    /**
     * Get Error Message
     *
     * @return string the error message
     */
    public function getErrorMessage()
    {
        return $this->error["message"];
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
            "asyncJobId" => $this->asyncJobId,
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
     * @return PlainResponse
     */
    public function reload($data, $type)
    {
        $data = ($type == DumpType::$JSON) ? json_decode($data, true) : $data;
        $this->response = $data["response"];
        $this->callback = $data["callback"];
        $this->items = $data["items"];
        $this->asyncJob = $data["asyncJob"];
        $this->asyncJobId = $data["asyncJobId"];
        $this->error = $data["error"];

        return $this;
    }
}