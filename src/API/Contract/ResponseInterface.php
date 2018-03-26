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
     * Set Response
     */
    public function setResponse($response);

    /**
     * Set Async Job
     */
    public function setAsyncJob($asyncJob);

    /**
     * Set Async Job Id
     */
    public function setAsyncJobId($asyncJobId);

    /**
     * Set Error
     */
    public function setError($error);

    /**
     * Add Response Item
     */
    public function addItem($key, $value);

    /**
     * Set Callback
     */
    public function setCallback($callbackMethod = null, $callbackArguments = []);

    /**
     * Check if Item Exists
     */
    public function itemExists($key);

    /**
     * Get Response
     */
    public function getResponse();

    /**
     * Get Async Job
     */
    public function getAsyncJob();

    /**
     * Get Async Job Id
     */
    public function getAsyncJobId();

    /**
     * Get Response Item
     */
    public function getItem($key);

    /**
     * Get Callback
     */
    public function getCallback()

    /**
     * Get The Error
     */
    public function getError();

    /**
     * Get Plain Error
     */
    public function getPlainError()

    /**
     * Get Parsed Error
     */
    public function getParsedError()

    /**
     * Get Error Code
     */
    public function getErrorCode()

    /**
     * Get Error Message
     */
    public function getErrorMessage();

    /**
     * Dump The PlainResponse Instance Data
     */
    public function dump($type)

    /**
     * Reload The PlainResponse Instance Data
     */
    public function reload($data, $type);
}