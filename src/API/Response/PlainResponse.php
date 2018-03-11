<?php
namespace Clivern\CloudStackMonkey\API\Response;

/**
 * @since 1.0.0
 * @package Clivern\CloudStackMonkey\API\Response
 */
class PlainResponse {

    private $rawResponse;
    private $response;
    private $callback;


    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    public function addRawResponse($rawResponse)
    {
        $this->rawResponse = $rawResponse;

        return $this;
    }

    public function getRawResponse()
    {
        return $this->rawResponse
    }

    public function setCallback($callback)
    {
        $this->callback = $callback;

        return $this;
    }

    public function getCallback()
    {
        return $this->callback;
    }

}