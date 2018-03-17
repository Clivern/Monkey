<?php
namespace Clivern\Monkey\API\Contract;

/**
 * API Response Interface
 *
 * @since 1.0.0
 * @package Clivern\Monkey\API\Contract
 */
interface ResponseInterface {

    /**
     * Set The Raw Response
     */
    public function setRawResponse($rawResponse);

    /**
     * Get The Raw Response
     */
    public function getRawResponse();

    /**
     * Get Response As Array
     */
    public function getResponse();

    /**
     * Set Response As Array
     */
    public function setResponse($response);

    /**
     * Set Status Code
     */
    public function setStatusCode($statusCode);

    /**
     * Get Status Code
     */
    public function getStatusCode();

    /**
     * Add Response Item
     */
    public function addItem($key, $value);

    /**
     * Get Response Item
     */
    public function getItem($key);

    /**
     * Set Callback
     */
    public function setCallback($callback);

    /**
     * Get Callback
     */
    public function getCallback();
}