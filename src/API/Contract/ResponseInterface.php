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
     * Get Response
     */
    public function getResponse();

    /**
     * Set Response
     */
    public function setResponse($response);

    /**
     * Get Async Job
     */
    public function getAsyncJob();

    /**
     * Set Async Job
     */
    public function setAsyncJob($asyncJob);

    /**
     * Set Async Job Id
     */
    public function setAsyncJobId($asyncJobId);

    /**
     * Get Async Job Id
     */
    public function getAsyncJobId();

    /**
     * Set Error
     */
    public function setError($error);

    /**
     * Get The Error
     */
    public function getError();

    /**
     * Get Error As String
     */
    public function getPlainError();

    /**
     * Get Parsed Error
     */
    public function getParsedError();

    /**
     * Get Error Code
     */
    public function getErrorCode();

    /**
     * Get Error Message
     */
    public function getErrorMessage();

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
    public function setCallback($callbackMethod = null, $callbackArguments = []);

    /**
     * Get Callback
     */
    public function getCallback();

    /**
     * Dump The PlainResponse Instance Data
     */
    public function dump($type);

    /**
     * Reload The PlainResponse Instance Data
     */
    public function reload($data, $type);
}