<?php

/*
 * This file is part of Monkey - Apache CloudStack SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Monkey\API\Contract;

/**
 * API Response Interface.
 *
 * @since 1.0.0
 */
interface ResponseInterface
{
    /**
     * Set Response.
     *
     * @param mixed $response
     */
    public function setResponse($response);

    /**
     * Set Async Job.
     *
     * @param mixed $asyncJob
     */
    public function setAsyncJob($asyncJob);

    /**
     * Set Async Job Id.
     *
     * @param mixed $asyncJobId
     */
    public function setAsyncJobId($asyncJobId);

    /**
     * Set Error.
     *
     * @param mixed $error
     */
    public function setError($error);

    /**
     * Set Error Code.
     *
     * @param mixed $code
     */
    public function setErrorCode($code);

    /**
     * Set Error Message.
     *
     * @param mixed $message
     */
    public function setErrorMessage($message);

    /**
     * Add Response Item.
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function addItem($key, $value);

    /**
     * Set Callback.
     *
     * @param null|mixed $callbackMethod
     * @param mixed      $callbackArguments
     */
    public function setCallback($callbackMethod = null, $callbackArguments = []);

    /**
     * Check if Item Exists.
     *
     * @param mixed $key
     */
    public function itemExists($key);

    /**
     * Get Response.
     */
    public function getResponse();

    /**
     * Get Async Job.
     */
    public function getAsyncJob();

    /**
     * Get Async Job Id.
     */
    public function getAsyncJobId();

    /**
     * Get Response Item.
     *
     * @param mixed $key
     */
    public function getItem($key);

    /**
     * Get Callback.
     */
    public function getCallback();

    /**
     * Get The Error.
     */
    public function getError();

    /**
     * Get Plain Error.
     */
    public function getPlainError();

    /**
     * Get Parsed Error.
     */
    public function getParsedError();

    /**
     * Get Error Code.
     */
    public function getErrorCode();

    /**
     * Get Error Message.
     */
    public function getErrorMessage();

    /**
     * Dump The PlainResponse Instance Data.
     *
     * @param mixed $type
     */
    public function dump($type);

    /**
     * Reload The PlainResponse Instance Data.
     *
     * @param mixed $data
     * @param mixed $type
     */
    public function reload($data, $type);
}
