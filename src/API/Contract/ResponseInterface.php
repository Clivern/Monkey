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
     * Get Response As Array
     */
    public function getResponse();

    /**
     * Set Response As Array
     */
    public function setResponse($response);

    /**
     * Get Async Job As Array
     */
    public function getAsyncJob();

    /**
     * Set Async Job As Array
     */
    public function setAsyncJob($asyncJob);

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